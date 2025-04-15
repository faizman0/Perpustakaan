@extends('layouts.main')

@section('title', 'Edit Anggota')

@section('content')
<div class="container-fluid">
    <h2>Edit Member</h2>
    <form action="{{ route('members.update', $member->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $member->name }}" required>
        </div>
        <div class="form-group">
            <label for="nis">NIS</label>
            <input type="text" class="form-control" id="nis" name="nis" value="{{ $member->nis }}" required>
        </div>
        <div class="form-group">
            <label for="class">Kelas</label>
            <input type="text" class="form-control" id="class" name="class" value="{{ $member->class }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('members.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection