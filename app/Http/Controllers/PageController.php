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
        $today = \Carbon\Carbon::today();
        return view('dashboard.index', [
            'totalKunjunganHariIni' => \App\Models\Kunjungan::whereDate('tanggal_kunjungan', $today)->count(),
            'totalPeminjamanHariIni' => \App\Models\Peminjaman::from('peminjamen')->whereDate('tanggal_pinjam', $today)->count(),
            'totalKunjunganBulanIni' => \App\Models\Kunjungan::whereMonth('tanggal_kunjungan', now()->month)->count(),
            'totalPeminjamanBulanIni' => \App\Models\Peminjaman::from('peminjamen')->whereMonth('tanggal_pinjam', now()->month)->count(),
            'totalAnggota' => \App\Models\Anggota::count(),
            'totalBukus' => \App\Models\Buku::count(),
            'key'=>'dashboard'
        ]);
    }
}
