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

        try {
            $peminjamanBelumKembali = Peminjaman::whereDoesntHave('pengembalian')
                ->with(['buku', 'anggota.siswa', 'anggota.guru'])
                ->get();

            $pengembalians = Pengembalian::with(['peminjaman.anggota.siswa', 'peminjaman.anggota.guru', 'peminjaman.buku.kategori'])
                ->latest()
                ->get();

            return view('pengembalian.index', [
                'peminjamanBelumKembali' => $peminjamanBelumKembali,
                'pengembalians' => $pengembalians,
                'key' => 'pengembalian'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in PengembalianController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengambil data pengembalian');
        }
    }

    public function create()
    {
        $this->middleware('role:admin|petugas');
        $peminjaman = Peminjaman::whereDoesntHave('pengembalian')
            ->with(['buku', 'anggota.siswa', 'anggota.guru'])
            ->get();
        return view('pengembalian.create', [
            'peminjaman' => $peminjaman,
            'key' => 'pengembalian'
        ]);
    }

    public function store(Request $request)
    {
        $this->middleware('role:admin|petugas');
        $validatedData = $request->validate([
            'peminjaman_id' => 'required|exists:peminjamen,id',
            'tanggal_kembali' => 'required|date'
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
            Pengembalian::create($validatedData);
            
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

    public function destroy(Pengembalian $pengembalian)
    {
        $this->middleware('role:admin');
        
        try {
            DB::beginTransaction();

            // Temukan peminjaman terkait
            $peminjaman = Peminjaman::find($pengembalian->peminjaman_id);

            // Jika pengembalian dihapus, stok buku kembali berkurang
            if ($peminjaman) {
                $peminjaman->buku->decrement('jumlah');
            }

            // Hapus pengembalian
            $pengembalian->delete();

            DB::commit();

            return redirect()->back()
                ->with('success', 'Data pengembalian berhasil dihapus. Stok buku telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in PengembalianController@destroy: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data pengembalian');
        }
    }

    public function exportPdf(Request $request)
    {
        $this->middleware('role:admin');
        
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query = Pengembalian::with(['peminjaman.anggota.siswa', 'peminjaman.anggota.guru', 'peminjaman.buku.kategori']);
            
            if ($startDate && $endDate) {
                $query->whereBetween('tanggal_kembali', [$startDate, $endDate]);
            }

            $pengembalians = $query->latest()->get();

            // Get active loans for comparison
            $peminjamanBelumKembali = Peminjaman::whereDoesntHave('pengembalian')
                ->with(['buku', 'anggota.siswa', 'anggota.guru'])
                ->get();

            $pdf = PDF::loadView('pengembalian.pdf', [
                'pengembalians' => $pengembalians,
                'peminjamanBelumKembali' => $peminjamanBelumKembali,
                'startDate' => $startDate,
                'endDate' => $endDate
            ]);

            return $pdf->download('laporan-pengembalian.pdf');
        } catch (\Exception $e) {
            Log::error('Error in PengembalianController@exportPdf: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export PDF');
        }
    }

    public function exportExcel(Request $request)
    {
        $this->middleware('role:admin');
        
        try {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            $query = Pengembalian::with(['peminjaman.anggota.siswa', 'peminjaman.anggota.guru', 'peminjaman.buku.kategori']);
            
            if ($startDate && $endDate) {
                $query->whereBetween('tanggal_kembali', [$startDate, $endDate]);
            }

            $pengembalians = $query->latest()->get();

            return Excel::download(new PengembalianExport($pengembalians), 'laporan-pengembalian.xlsx');
        } catch (\Exception $e) {
            Log::error('Error in PengembalianController@exportExcel: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat export Excel');
        }
    }

    public function show(Pengembalian $pengembalian)
    {
        $this->middleware('role:admin|petugas');
        return response()->json($pengembalian->load(['peminjaman.anggota.siswa', 'peminjaman.anggota.guru', 'peminjaman.buku.kategori']));
    }
}