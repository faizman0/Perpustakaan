@extends('layouts.main')

@section('title', 'Edit Pengembalian')

@section('content')
<div class="container-fluid">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Terjadi Kesalahan!</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Pengembalian</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.pengembalian.update', $pengembalian->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Pilih Peminjaman <span class="text-warning">*</span></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">Pilih</th>
                                        <th>Peminjam</th>
                                        <th>Buku</th>
                                        <th>Tanggal Pinjam</th>
                                        <th>Jatuh Tempo</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($peminjaman as $p)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           name="peminjaman_id" value="{{ $p->id }}" 
                                                           id="peminjaman_{{ $p->id }}"
                                                           {{ $pengembalian->peminjaman_id == $p->id ? 'checked' : '' }}
                                                           required>
                                                </div>
                                            </td>
                                            <td>
                                                @if($p->siswa_id)
                                                    {{ $p->siswa->nama }} ({{ $p->siswa->nis }})
                                                    <br>
                                                    <small class="text-muted">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</small>
                                                @else
                                                    {{ $p->guru->nama }} ({{ $p->guru->nip }})
                                                @endif
                                            </td>
                                            <td>{{ $p->buku->judul }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(7)->format('d/m/Y') }}</td>
                                            <td>
                                                @php
                                                    $jatuhTempo = $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(7) : null;
                                                    $now = \Carbon\Carbon::now();
                                                    $terlambat = $jatuhTempo ? $now->diffInDays($jatuhTempo, false) : 0;
                                                @endphp
                                                @if($terlambat < 0)
                                                    <span class="badge bg-danger">Terlambat {{ abs($terlambat) }} hari</span>
                                                @else
                                                    <span class="badge bg-success">Tepat Waktu</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">Tidak ada data peminjaman yang tersedia</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Informasi Pengembalian</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_kembali" class="form-label">Tanggal Kembali <span class="text-warning">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" 
                                           value="{{ $pengembalian->tanggal_kembali->format('Y-m-d') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3">{{ $pengembalian->keterangan }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.pengembalian.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection