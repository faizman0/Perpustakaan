@extends('layouts.main')

@section('title', 'Edit Guru')

@section('content')
    @if(auth()->user()->hasRole('admin'))
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Guru</h5>
                    </div>

                    <div class="card-body">
                        <form action="{{ url('/admin/guru/'.$guru->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="nama" class="form-label">Nama</label>
                                <input type="text" class="form-control @error('nama') is-invalid @enderror" 
                                    id="nama" name="nama" value="{{ old('nama', $guru->nama) }}" required>
                                @error('nama')
                                    <div class="invalid-feedback"></div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nip" class="form-label">NIP</label>
                                <input type="text" class="form-control @error('nip') is-invalid @enderror" 
                                    id="nip" name="nip" value="{{ old('nip', $guru->nip) }}" required>
                                @error('nip')
                                    <div class="invalid-feedback"></div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                                <select class="form-control @error('jenis_kelamin') is-invalid @enderror" 
                                    id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <div class="invalid-feedback"></div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('admin.guru.index') }}" class="btn btn-secondary">Kembali</a>
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="container">
        <div class="alert alert-danger">
            Anda tidak memiliki akses ke halaman ini.
        </div>
        <a href="{{ route('guru.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
    @endif
@endsection