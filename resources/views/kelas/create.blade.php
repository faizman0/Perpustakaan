@extends('layouts.main')

@section('title', 'Tambah Kelas')

@section('content')
    <form action="{{ route('admin.kelas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" required>
        </div>
        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection
