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
                                <th>Peminjam</th>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Tidak ada data peminjaman yang perlu dikembalikan</td>
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
                            <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" 
                                   value="{{ date('Y-m-d') }}" required>
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