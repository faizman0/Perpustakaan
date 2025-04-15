@extends('layouts.main')

@section('title', 'Tambah Anggota')

@section('content')
<div class="container-fluid">
    <h2>Tambah Anggota</h2>
    <form action="{{ route('members.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nama</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="nis">NIS</label>
            <input type="text" class="form-control" id="nis" name="nis" required>
        </div>
        <div class="form-group">
            <label for="class">Kelas</label>
            <input type="text" class="form-control" id="class" name="class" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Submit</button>
        <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection