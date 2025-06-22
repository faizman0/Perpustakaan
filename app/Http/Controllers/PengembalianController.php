<?php

namespace App\Http\Controllers;

use App\Exports\PengembalianExport;
use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class PengembalianController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->middleware('role:admin|petugas');
        $pengembalianSiswa = Pengembalian::with(['peminjaman.siswa.kelas', 'peminjaman.buku.kategori'])
            ->whereHas('peminjaman', function($query) {
                $query->whereNotNull('siswa_id');
            })
            ->latest()
            ->get();
        
        $pengembalianGuru = Pengembalian::with(['peminjaman.guru', 'peminjaman.buku.kategori'])
            ->whereHas('peminjaman', function($query) {
                $query->whereNotNull('guru_id');
            })
            ->latest()
            ->get();

        $peminjamanBelumKembali = Peminjaman::whereDoesntHave('pengembalian')
            ->with(['buku', 'siswa.kelas', 'guru'])
            ->get();

        $pengembalians = Pengembalian::with(['peminjaman.siswa.kelas', 'peminjaman.guru', 'peminjaman.buku.kategori'])
            ->latest()
            ->get();
            
        return view('pengembalian.index', [
            'pengembalianSiswa' => $pengembalianSiswa,
            'pengembalianGuru' => $pengembalianGuru,
            'peminjamanBelumKembali' => $peminjamanBelumKembali,
            'pengembalians' => $pengembalians,
            'key' => 'pengembalian'
        ]);
    }

    public function create()
    {
        $this->middleware('role:admin|petugas');
        $peminjaman = Peminjaman::whereDoesntHave('pengembalian')
            ->with(['buku', 'siswa.kelas', 'guru'])
            ->get();
        return view('pengembalian.create', [
            'peminjaman' => $peminjaman,
            'key' => 'pengembalian'
        ]);
    }

    public function store(Request $request)
    {
        $this->middleware('role:admin|petugas');
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjamen,id',
            'tanggal_kembali' => 'required|date',
            'keterangan' => 'nullable'
        ]);

        try {
            DB::beginTransaction();

            // Cek apakah peminjaman sudah dikembalikan
            $peminjaman = Peminjaman::findOrFail($request->peminjaman_id);
            if ($peminjaman->pengembalian()->exists()) {
                return redirect()->back()
                    ->with('error', 'Buku sudah dikembalikan')
                    ->withInput();
            }

            // Tambah stok buku
            $peminjaman->buku->increment('jumlah');

            // Buat pengembalian
            $pengembalian = Pengembalian::create($request->all());

            DB::commit();

            // Redirect based on user role
            if (auth()->user()->hasRole('admin')) {
                return redirect()->route('admin.pengembalian.index')
                    ->with('success', 'Data pengembalian berhasil ditambahkan');
            } else {
                return redirect()->route('petugas.pengembalian.index')
                    ->with('success', 'Data pengembalian berhasil ditambahkan');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PengembalianController@store: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data pengembalian')
                ->withInput();
        }
    }

    public function edit(Pengembalian $pengembalian)
    {
        $this->middleware('role:admin');
        $peminjaman = Peminjaman::whereDoesntHave('pengembalian')
            ->orWhere('id', $pengembalian->peminjaman_id)
            ->with(['buku', 'siswa.kelas', 'guru'])
            ->get();
        return view('pengembalian.edit', [
            'pengembalian' => $pengembalian,
            'peminjaman' => $peminjaman,
            'key' => 'pengembalian'
        ]);
    }

    public function update(Request $request, Pengembalian $pengembalian)
    {
        $this->middleware('role:admin');
        $request->validate([
            'peminjaman_id' => 'required|exists:peminjaman,id',
            'tanggal_kembali' => 'required|date',
            'keterangan' => 'nullable'
        ]);

        try {
            DB::beginTransaction();

            // Jika peminjaman diubah
            if ($pengembalian->peminjaman_id != $request->peminjaman_id) {
                // Kembalikan stok buku lama
                $peminjamanLama = Peminjaman::find($pengembalian->peminjaman_id);
                $peminjamanLama->buku->increment('jumlah');

                // Cek apakah peminjaman baru sudah dikembalikan
                $peminjamanBaru = Peminjaman::find($request->peminjaman_id);
                if ($peminjamanBaru->pengembalian()->exists()) {
                    return redirect()->back()
                        ->with('error', 'Buku sudah dikembalikan')
                        ->withInput();
                }

                // Kurangi stok buku baru
                $peminjamanBaru->buku->decrement('jumlah');
            }

            $pengembalian->update($request->all());

            DB::commit();

            return redirect()->route('admin.pengembalian.index')
                ->with('success', 'Data pengembalian berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PengembalianController@update: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui data pengembalian')
                ->withInput();
        }
    }

    public function destroy(Pengembalian $pengembalian)
    {
        $this->middleware('role:admin');
        
        try {
            DB::beginTransaction();

            // Kembalikan stok buku
            $peminjaman = Peminjaman::find($pengembalian->peminjaman_id);
            $peminjaman->buku->increment('jumlah');

            $pengembalian->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Data pengembalian berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PengembalianController@destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data pengembalian');
        }
    }

    public function exportPdf()
    {
        $this->middleware('role:admin');
        $pengembalianSiswa = Pengembalian::with(['peminjaman.siswa.kelas', 'peminjaman.buku.kategori'])
            ->whereHas('peminjaman', function($query) {
                $query->whereNotNull('siswa_id');
            })
            ->latest()
            ->get();
        
        $pengembalianGuru = Pengembalian::with(['peminjaman.guru', 'peminjaman.buku.kategori'])
            ->whereHas('peminjaman', function($query) {
                $query->whereNotNull('guru_id');
            })
            ->latest()
            ->get();

        $pdf = PDF::loadView('pengembalian.pdf', [
            'pengembalianSiswa' => $pengembalianSiswa,
            'pengembalianGuru' => $pengembalianGuru
        ]);

        return $pdf->download('laporan-pengembalian.pdf');
    }

    public function exportExcel()
    {
        $this->middleware('role:admin');
        $pengembalianSiswa = Pengembalian::with(['peminjaman.siswa.kelas', 'peminjaman.buku.kategori'])
            ->whereHas('peminjaman', function($query) {
                $query->whereNotNull('siswa_id');
            })
            ->latest()
            ->get();
        
        $pengembalianGuru = Pengembalian::with(['peminjaman.guru', 'peminjaman.buku.kategori'])
            ->whereHas('peminjaman', function($query) {
                $query->whereNotNull('guru_id');
            })
            ->latest()
            ->get();

        return Excel::download(new PengembalianExport($pengembalianSiswa, $pengembalianGuru), 'laporan-pengembalian.xlsx');
    }
}