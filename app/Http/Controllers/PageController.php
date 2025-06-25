<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Kunjungan;
use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Pengembalian;
use Carbon\Carbon;

class PageController extends Controller
{
    public function index()
    {
        // Data untuk dashboard
        // $totalBuku = Buku::count();
        // $totalSiswa = Siswa::count();
        // $totalPeminjaman = Peminjaman::from('peminjamen')->count(); // Changed table name to peminjamen
        // $totalKunjungan = Kunjungan::count();
        $today = Carbon::today();

        // Data untuk grafik atau statistik
        // $peminjamanTerbaru = Peminjaman::from('peminjamen') // Changed table name
        //     ->with(['siswa', 'buku'])
        //     ->latest()
        //     ->take(5)
        //     ->get();

        // $kunjunganTerbaru = Kunjungan::with('siswa')
        //     ->latest()
        //     ->take(5)
        //     ->get();

        return view('dashboard.index', [
            'totalKunjunganHariIni' => Kunjungan::whereDate('tanggal_kunjungan', $today)->count(),
            'totalPeminjamanHariIni' => Peminjaman::from('peminjamen')->whereDate('tanggal_pinjam', $today)->count(), // Changed table name
            
            'totalKunjunganBulanIni' => Kunjungan::whereMonth('tanggal_kunjungan', now()->month)->count(),
            'totalPeminjamanBulanIni' => Peminjaman::from('peminjamen')->whereMonth('tanggal_pinjam', now()->month)->count(), // Changed table name
            'totalAnggota' => Anggota::count(),
            'totalBukus' => Buku::count(),
            'key'=>'dashboard'
        ]);
    }
}
