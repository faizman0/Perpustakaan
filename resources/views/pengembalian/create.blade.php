@extends('layouts.main')

@section('title', 'Tambah Pengembalian')

@section('content')
<div class="container-fluid">
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('pengembalian.store') }}" method="POST">
        @csrf
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pilih Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th width="5%">Pilih</th>
                                <th>Kode Anggota</th>
                                <th>Peminjam</th>
                                <th>Tipe</th>
                                <th>Buku</th>
                                <th>Tanggal Pinjam</th>
                                <th>Jatuh Tempo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($peminjaman as $p)
                                <tr>
                                    <td>
                                        <input type="radio" name="peminjaman_id" value="{{ $p->id }}" required>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $p->anggota->kode_anggota }}</span>
                                    </td>
                                    <td>
                                        {{ $p->anggota->nama }}
                                        <br>
                                        <small class="text-muted">
                                            @if($p->anggota->siswa)
                                                {{ $p->anggota->siswa->nis }}
                                            @elseif($p->anggota->guru)
                                                {{ $p->anggota->guru->nip }}
                                            @endif
                                        </small>
                                    </td>
                                    <td>
                                        @if($p->anggota->tipe == 'Siswa')
                                            <span class="badge badge-success">Siswa</span>
                                            @if($p->anggota->siswa)
                                                <br><small class="text-muted">{{ $p->anggota->siswa->kelas->nama_kelas ?? '-' }}</small>
                                            @endif
                                        @else
                                            <span class="badge badge-info">Guru</span>
                                        @endif
                                    </td>
                                    <td>{{ $p->buku->judul }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(14)->format('d/m/Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Tidak ada data peminjaman yang perlu dikembalikan</td>
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
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="datetime-local" class="form-control" id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali', now('Asia/Jakarta')->format('Y-m-d\TH:i')) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                      placeholder="Masukkan keterangan pengembalian"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Pengembalian
            </button>
        </div>
    </form>
</div>
@endsection 