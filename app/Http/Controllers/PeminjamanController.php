<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Siswa;
use App\Models\Guru;
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
        $peminjamanSiswa = Peminjaman::with(['siswa.kelas', 'buku.kategori'])
            ->whereNotNull('siswa_id')
            ->latest()
            ->get();
        
        $peminjamanGuru = Peminjaman::with(['guru', 'buku.kategori'])
            ->whereNotNull('guru_id')
            ->latest()
            ->get();
            
        return view('peminjaman.index', [
            'peminjamanSiswa' => $peminjamanSiswa,
            'peminjamanGuru' => $peminjamanGuru,
            'key' => 'peminjaman'
        ]);
    }

    public function create()
    {
        $this->middleware('role:admin|petugas');
        $bukus = Buku::where('jumlah', '>', 0)->get();
        $siswas = Siswa::all();
        $gurus = Guru::all();
        return view('peminjaman.create', [
            'bukus' => $bukus,
            'siswas' => $siswas,
            'gurus' => $gurus,
            'key' => 'peminjaman'
        ]);
    }

    public function store(Request $request)
    {
        $this->middleware('role:admin|petugas');
        $request->validate([
            'buku_ids' => 'required|array|min:1|max:3',
            'buku_ids.*' => 'exists:bukus,id',
            'siswa_id' => 'required_without:guru_id|exists:siswas,id',
            'guru_id' => 'required_without:siswa_id|exists:gurus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
        ]);

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
                'siswa_id' => $request->siswa_id,
                'guru_id' => $request->guru_id,
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
        $siswas = Siswa::all();
        $gurus = Guru::all();
        return view('peminjaman.edit', [
            'peminjaman' => $peminjaman,
            'bukus' => $bukus,
            'siswas' => $siswas,
            'gurus' => $gurus,
            'key' => 'peminjaman'
        ]);
    }

    public function update(Request $request, Peminjaman $peminjaman)
    {
        $this->middleware('role:admin');
        $request->validate([
            'buku_ids' => 'required|array|min:1|max:3',
            'buku_ids.*' => 'exists:bukus,id',
            'siswa_id' => 'required_without:guru_id|exists:siswas,id',
            'guru_id' => 'required_without:siswa_id|exists:gurus,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'required|date|after:tanggal_pinjam',
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
        $peminjamanSiswa = Peminjaman::with(['siswa.kelas', 'buku.kategori'])
            ->whereNotNull('siswa_id')
            ->latest()
            ->get();
        
        $peminjamanGuru = Peminjaman::with(['guru', 'buku.kategori'])
            ->whereNotNull('guru_id')
            ->latest()
            ->get();

        $pdf = PDF::loadView('peminjaman.pdf', [
            'peminjamanSiswa' => $peminjamanSiswa,
            'peminjamanGuru' => $peminjamanGuru
        ]);

        return $pdf->download('laporan-peminjaman.pdf');
    }

    public function exportExcel()
    {
        $this->middleware('role:admin');
        $peminjamanSiswa = Peminjaman::with(['siswa.kelas', 'buku.kategori'])
            ->whereNotNull('siswa_id')
            ->latest()
            ->get();
        
        $peminjamanGuru = Peminjaman::with(['guru', 'buku.kategori'])
            ->whereNotNull('guru_id')
            ->latest()
            ->get();

        return Excel::download(new PeminjamanExport($peminjamanSiswa, $peminjamanGuru), 'laporan-peminjaman.xlsx');
    }
}