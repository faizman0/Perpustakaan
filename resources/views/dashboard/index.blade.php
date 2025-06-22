@extends('layouts.main')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card shadow rounded-lg bg-primary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Total Siswa</h5>
                        <h3 class="mb-0">{{ $totalSiswas }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow rounded-lg bg-success text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Total Buku</h5>
                        <h3 class="mb-0">{{ $totalBukus }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow rounded-lg bg-info text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-user-clock fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Kunjungan Hari Ini</h5>
                        <h3 class="mb-0">{{ $totalKunjunganHariIni }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow rounded-lg bg-warning text-dark">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-book-reader fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Peminjaman Hari Ini</h5>
                        <h3 class="mb-0">{{ $totalPeminjamanHariIni }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow rounded-lg bg-danger text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-book-open fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Peminjaman Aktif</h5>
                        <h3 class="mb-0">{{ $totalPeminjamanAktif }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow rounded-lg bg-secondary text-white">
                <div class="card-body d-flex align-items-center">
                    <div class="mr-3">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                    <div>
                        <h5 class="card-title mb-1">Kunjungan Bulan Ini</h5>
                        <h3 class="mb-0">{{ $totalKunjunganBulanIni }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
