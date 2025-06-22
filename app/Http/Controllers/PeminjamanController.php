<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\PeminjamanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class PeminjamanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $this->middleware('role:admin|petugas');
        $peminjamans = Peminjaman::with(['anggota.siswa', 'anggota.guru', 'buku.kategori'])
            ->latest()
            ->get();
            
        return view('peminjaman.index', [
            'peminjamans' => $peminjamans,
            'key' => 'peminjaman'
        ]);
    }

    public function create()
    {
        $this->middleware('role:admin|petugas');
        $bukus = Buku::where('jumlah', '>', 0)->get();
        $anggotas = Anggota::with(['siswa', 'guru'])->get();
        return view('peminjaman.create', [
            'bukus' => $bukus,
            'anggotas' => $anggotas,
            'key' => 'peminjaman'
        ]);
    }

    public function store(Request $request)
    {
        $this->middleware('role:admin|petugas');
        $request->validate([
            'buku_ids' => 'required|array|min:1|max:3',
            'buku_ids.*' => 'exists:bukus,id',
            'anggota_id' => 'required|exists:anggotas,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date|after:tanggal_pinjam',
        ]);

        // Cek apakah anggota sudah meminjam buku yang sama dan belum dikembalikan
        foreach ($request->buku_ids as $bukuId) {
            $existingPeminjaman = Peminjaman::where('anggota_id', $request->anggota_id)
                ->where('buku_id', $bukuId)
                ->whereDoesntHave('pengembalian')
                ->first();

            if ($existingPeminjaman) {
                $buku = Buku::find($bukuId);
                return redirect()->back()
                    ->with('error', 'Anggota sudah meminjam buku "' . $buku->judul . '" dan belum dikembalikan.')
                    ->withInput();
            }
        }

        // Cek stok buku untuk setiap buku yang dipilih
        foreach ($request->buku_ids as $bukuId) {
            $buku = Buku::findOrFail($bukuId);
            if ($buku->jumlah <= 0) {
                return redirect()->back()
                    ->with('error', 'Stok buku tidak tersedia')
                    ->withInput();
            }
        }

        // Kurangi stok buku untuk setiap buku yang dipilih
        foreach ($request->buku_ids as $bukuId) {
            $buku = Buku::findOrFail($bukuId);
            $buku->decrement('jumlah');
            
            // Buat peminjaman untuk setiap buku
            Peminjaman::create([
                'buku_id' => $bukuId,
                'anggota_id' => $request->anggota_id,
                'tanggal_pinjam' => $request->tanggal_pinjam,
                'tanggal_kembali' => $request->tanggal_kembali,
            ]);
        }

        // Redirect based on user role
        if (auth()->user()->hasRole('admin')) {
            return redirect()->route('admin.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil ditambahkan');
        } else {
            return redirect()->route('petugas.peminjaman.index')
                ->with('success', 'Data peminjaman berhasil ditambahkan');
        }
    }

    public function edit(Peminjaman $peminjaman)
    {
        $this->middleware('role:admin');
        $bukus = Buku::all();
        $anggotas = Anggota::with(['siswa', 'guru'])->get();
        return view('peminjaman.edit', [
            'peminjaman' => $peminjaman,
            'bukus' => $bukus,
            'anggotas' => $anggotas,
            'key' => 'peminjaman'
        ]);
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $this->middleware('role:admin');
        $request->validate([
            'buku_id' => 'required|exists:bukus,id',
            'anggota_id' => 'required|exists:anggotas,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => '|date|after:tanggal_pinjam',
            'keterangan' => 'nullable'
        ]);

        // Jika buku diubah, kembalikan stok buku lama dan kurangi stok buku baru
        if ($peminjaman->buku_id != $request->buku_id) {
            $bukuLama = Buku::find($peminjaman->buku_id);
            $bukuLama->increment('jumlah');

            $bukuBaru = Buku::find($request->buku_id);
            if ($bukuBaru->jumlah <= 0) {
                return redirect()->back()
                    ->with('error', 'Stok buku tidak tersedia')
                    ->withInput();
            }
            $bukuBaru->decrement('jumlah');
        }

        $peminjaman->update($request->all());

        return redirect()->route('admin.peminjaman.index')
            ->with('success', 'Data peminjaman berhasil diperbarui');
    }

    public function destroy(Peminjaman $peminjaman)
    {
        $this->middleware('role:admin');
        // Kembalikan stok buku
        $buku = Buku::find($peminjaman->buku_id);
        $buku->increment('jumlah');

        $peminjaman->delete();

        return redirect()->back()
            ->with('success', 'Data peminjaman berhasil dihapus');
    }

    public function exportPdf()
    {
        $this->middleware('role:admin');
        $peminjamans = Peminjaman::with(['anggota.siswa', 'anggota.guru', 'buku.kategori'])
            ->latest()
            ->get();

        $pdf = PDF::loadView('peminjaman.pdf', [
            'peminjamans' => $peminjamans
        ]);

        return $pdf->download('laporan-peminjaman.pdf');
    }

    public function exportExcel()
    {
        $this->middleware('role:admin');
        $peminjamans = Peminjaman::with(['anggota.siswa', 'anggota.guru', 'buku.kategori'])
            ->latest()
            ->get();

        return Excel::download(new PeminjamanExport($peminjamans), 'laporan-peminjaman.xlsx');
    }

    /**
     * Export PDF untuk satu data peminjaman
     */
    public function exportSinglePdf($id)
    {
        $this->middleware('role:admin');
        $peminjaman = Peminjaman::with(['anggota.siswa', 'anggota.guru', 'buku.kategori'])->findOrFail($id);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('peminjaman.bukti', [
            'peminjaman' => $peminjaman
        ]);
        return $pdf->download('bukti-peminjaman-'.$peminjaman->id.'.pdf');
    }
}