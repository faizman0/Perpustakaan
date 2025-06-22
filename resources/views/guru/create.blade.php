@extends('layouts.main')

@section('content')
    <div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Tambah Guru Baru</h5>
                </div>

                <div class="card-body">
                <form action="{{ auth()->user()->hasRole('admin') ? route('admin.guru.store') : route('petugas.guru.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama</label>
                            <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" required>
                            @error('nama')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input type="number" class="form-control @error('nip') is-invalid @enderror" id="nip" name="nip" value="{{ old('nip') }}" required>
                            @error('nip')   
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                        <label for="jenis_kelamin">Jenis Kelamin</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" class="form-control @error('jenis_kelamin') is-invalid @enderror" value="{{ old('jenis_kelamin') }}" required>
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                            @error('jenis_kelamin')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>  

                        <div class="d-flex justify-content-between">
                            <a href="{{ auth()->user()->hasRole('admin') ? '/admin/guru' : '/petugas/guru' }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection