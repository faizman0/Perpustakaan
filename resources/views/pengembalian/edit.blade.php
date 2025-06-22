@extends('layouts.main')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Edit Pengembalian</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('pengembalian.update', $pengembalian->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="peminjaman_id" class="form-label">Peminjaman</label>
                            <select class="form-select @error('peminjaman_id') is-invalid @enderror" id="peminjaman_id" name="peminjaman_id" required>
                                <option value="">Pilih Peminjaman</option>
                                @foreach($peminjaman as $item)
                                    <option value="{{ $item->id }}" {{ old('peminjaman_id', $pengembalian->peminjaman_id) == $item->id ? 'selected' : '' }}>
                                        {{ $item->buku->judul }} - {{ $item->anggota->kode_anggota }} ({{ $item->anggota->nama }})
                                    </option>
                                @endforeach
                            </select>
                            @error('peminjaman_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                            <input type="datetime-local" class="form-control @error('tanggal_kembali') is-invalid @enderror" id="tanggal_kembali" name="tanggal_kembali" value="{{ old('tanggal_kembali', $pengembalian->tanggal_kembali->format('Y-m-d\TH:i')) }}" required>
                            @error('tanggal_kembali')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $pengembalian->keterangan) }}</textarea>
                            @error('keterangan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('pengembalian.index') }}" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection