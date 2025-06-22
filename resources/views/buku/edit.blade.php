@extends('layouts.main')

@section('title', 'Edit Buku')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Edit Buku</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.buku.update', $buku->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="kategori_id" class="form-label">Kategori</label>
                        <select class="form-control @error('kategori_id') is-invalid @enderror" id="kategori_id" name="kategori_id" required>
                            @foreach ($kategori as $k)
                                <option value="{{ $k->id }}" {{ old('kategori_id', $buku->kategori_id) == $k->id ? 'selected' : '' }}>{{ $k->nama }}</option>
                            @endforeach
                        </select>
                        @error('kategori_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $buku->judul) }}" required>
                        @error('judul')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="no_inventaris" class="form-label">No. Inventaris</label>
                        <input type="text" class="form-control @error('no_inventaris') is-invalid @enderror" id="no_inventaris" name="no_inventaris" value="{{ old('no_inventaris', $buku->no_inventaris) }}" required>
                        @error('no_inventaris')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="no_klasifikasi" class="form-label">No. Klasifikasi</label>
                        <input type="text" class="form-control @error('no_klasifikasi') is-invalid @enderror" id="no_klasifikasi" name="no_klasifikasi" value="{{ old('no_klasifikasi', $buku->no_klasifikasi) }}" required>
                        @error('no_klasifikasi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="pengarang" class="form-label">Pengarang</label>
                        <input type="text" class="form-control @error('pengarang') is-invalid @enderror" id="pengarang" name="pengarang" value="{{ old('pengarang', $buku->pengarang) }}" required>
                        @error('pengarang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="penerbit" class="form-label">Penerbit</label>
                        <input type="text" class="form-control @error('penerbit') is-invalid @enderror" id="penerbit" name="penerbit" value="{{ old('penerbit', $buku->penerbit) }}" required>
                        @error('penerbit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                        <input type="number" class="form-control @error('tahun_terbit') is-invalid @enderror" id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" required>
                        @error('tahun_terbit')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="edisi" class="form-label">Edisi</label>
                        <input type="text" class="form-control @error('edisi') is-invalid @enderror" id="edisi" name="edisi" value="{{ old('edisi', $buku->edisi) }}">
                        @error('edisi')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="isbn" class="form-label">ISBN</label>
                        <input type="text" class="form-control @error('isbn') is-invalid @enderror" id="isbn" name="isbn" value="{{ old('isbn', $buku->isbn) }}">
                        @error('isbn')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="kolase" class="form-label">Kolase</label>
                        <input type="text" class="form-control @error('kolase') is-invalid @enderror" id="kolase" name="kolase" value="{{ old('kolase', $buku->kolase) }}">
                        @error('kolase')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="jumlah" class="form-label">Jumlah</label>
                        <input type="number" class="form-control @error('jumlah') is-invalid @enderror" id="jumlah" name="jumlah" value="{{ old('jumlah', $buku->jumlah) }}" required>
                        @error('jumlah')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <input type="text" class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" value="{{ old('keterangan', $buku->keterangan) }}">
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">Kembali</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection