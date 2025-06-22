@extends('layouts.main')

@section('title', 'Tambah Buku')

@section('content')
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-book me-2"></i>Tambah Buku Baru</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.buku.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="kategori_id" class="form-label">Kategori</label>
                                <select class="form-select @error('kategori_id') is-invalid @enderror" 
                                        id="kategori_id" name="kategori_id" required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach ($kategori as $kategoris)
                                        <option value="{{ $kategoris->id }}" {{ old('kategori_id') == $kategoris->id ? 'selected' : '' }}>
                                            {{ $kategoris->nama }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kategori_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="judul" class="form-label">Judul Buku</label>
                                <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                       id="judul" name="judul" value="{{ old('judul') }}" required>
                                @error('judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_inventaris" class="form-label">Nomor Inventaris</label>
                                <input type="text" class="form-control @error('no_inventaris') is-invalid @enderror" 
                                       id="no_inventaris" name="no_inventaris" value="{{ old('no_inventaris') }}" required>
                                @error('no_inventaris')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="no_klasifikasi" class="form-label">Nomor Klasifikasi</label>
                                <input type="text" class="form-control @error('no_klasifikasi') is-invalid @enderror" 
                                       id="no_klasifikasi" name="no_klasifikasi" value="{{ old('no_klasifikasi') }}" required>
                                @error('no_klasifikasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="pengarang" class="form-label">Pengarang</label>
                                <input type="text" class="form-control @error('pengarang') is-invalid @enderror" 
                                       id="pengarang" name="pengarang" value="{{ old('pengarang') }}" required>
                                @error('pengarang')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="penerbit" class="form-label">Penerbit</label>
                                <input type="text" class="form-control @error('penerbit') is-invalid @enderror" 
                                       id="penerbit" name="penerbit" value="{{ old('penerbit') }}" required>
                                @error('penerbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                                <input type="number" class="form-control @error('tahun_terbit') is-invalid @enderror" 
                                       id="tahun_terbit" name="tahun_terbit" value="{{ old('tahun_terbit') }}" 
                                       min="1900" required>
                                @error('tahun_terbit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="edisi" class="form-label">Edisi/Cetakan</label>
                                <input type="text" class="form-control @error('edisi') is-invalid @enderror" 
                                       id="edisi" name="edisi" value="{{ old('edisi') }}">
                                @error('edisi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="isbn" class="form-label">ISBN</label>
                                <input type="text" class="form-control @error('isbn') is-invalid @enderror" 
                                       id="isbn" name="isbn" value="{{ old('isbn') }}" required>
                                @error('isbn')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="kolase" class="form-label">Kolase</label>
                                <input type="text" class="form-control @error('kolase') is-invalid @enderror" 
                                       id="kolase" name="kolase" value="{{ old('kolase') }}" >
                                @error('kolase')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah Stok</label>
                                <input type="number" class="form-control @error('jumlah') is-invalid @enderror" 
                                       id="jumlah" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required>
                                @error('jumlah')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea class="form-control @error('keterangan') is-invalid @enderror" 
                                  id="keterangan" name="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('admin.buku.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Simpan Buku
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection