@extends('layouts.main')

@section('title', 'Detail Anggota')

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Profile Image -->
        <div class="card card-primary card-outline">
            <div class="card-body box-profile">
                <div class="text-center">
                    <i class="fas fa-id-card fa-4x text-primary"></i>
                </div>

                <h3 class="profile-username text-center">{{ $anggota->nama }}</h3>
                <p class="text-muted text-center">
                    @if($anggota->tipe == 'Siswa')
                        <span class="badge badge-success">Siswa</span>
                    @else
                        <span class="badge badge-info">Guru</span>
                    @endif
                </p>

                <ul class="list-group list-group-unbordered mb-3">
                    <li class="list-group-item">
                        <b>Kode Anggota</b> <a class="float-right">{{ $anggota->kode_anggota }}</a>
                    </li>
                    <li class="list-group-item">
                        <b>NIS/NIP</b> 
                        <a class="float-right">
                            @if($anggota->siswa)
                                {{ $anggota->siswa->nis }}
                            @elseif($anggota->guru)
                                {{ $anggota->guru->nip }}
                            @endif
                        </a>
                    </li>
                    <li class="list-group-item">
                        <b>Jenis Kelamin</b> 
                        <a class="float-right">
                            @if($anggota->siswa)
                                {{ $anggota->siswa->jenis_kelamin }}
                            @elseif($anggota->guru)
                                {{ $anggota->guru->jenis_kelamin }}
                            @endif
                        </a>
                    </li>
                    @if($anggota->siswa)
                    <li class="list-group-item">
                        <b>Kelas</b> <a class="float-right">{{ optional($anggota->siswa->kelas)->nama_kelas ?? '-' }}</a>
                    </li>
                    @endif
                </ul>

                <div class="text-center">
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.anggota.index') : route('petugas.anggota.index') }}" class="btn btn-primary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    @if(auth()->user()->hasRole('admin'))
                    <form action="{{ route('admin.anggota.destroy', $anggota->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="card">
            <div class="card-header p-2">
                <ul class="nav nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#kunjungan" data-toggle="tab">
                            <i class="fas fa-calendar-check"></i> Kunjungan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#peminjaman" data-toggle="tab">
                            <i class="fas fa-book"></i> Peminjaman
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="active tab-pane" id="kunjungan">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal Kunjungan</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anggota->kunjungans as $index => $kunjungan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d/m/Y') }}</td>
                                        <td>{{ $kunjungan->keterangan }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada data kunjungan</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane" id="peminjaman">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Tanggal Kembali</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anggota->peminjamen as $index => $peminjaman)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $peminjaman->buku->judul }}</td>
                                        <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') }}</td>
                                        <td>
                                            @if($peminjaman->tanggal_kembali)
                                                {{ \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d/m/Y') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            {!! $peminjaman->status !!}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data peminjaman</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    $peminjamanAktif = $anggota->peminjamen->filter(function ($p) {
        return is_null($p->pengembalian);
    });
    $dikembalikanCount = $anggota->peminjamen->count() - $peminjamanAktif->count();

    $terlambatCount = $peminjamanAktif->filter(function ($p) {
        if (!$p->tanggal_pinjam) return false;
        return \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(14)->isPast();
    })->count();

    $dipinjamCount = $peminjamanAktif->count() - $terlambatCount;
@endphp

<!-- Statistik Card -->
<div class="row mt-4">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $anggota->kunjungans->count() }}</h3>
                <p>Total Kunjungan</p>
            </div>
            <div class="icon">
                <i class="fas fa-calendar-check"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $dipinjamCount }}</h3>
                <p>Sedang Dipinjam</p>
            </div>
            <div class="icon">
                <i class="fas fa-clock"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $terlambatCount }}</h3>
                <p>Terlambat</p>
            </div>
            <div class="icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $dikembalikanCount }}</h3>
                <p>Sudah Dikembalikan</p>
            </div>
            <div class="icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
</div>
@endsection 