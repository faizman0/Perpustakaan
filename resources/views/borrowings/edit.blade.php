@extends('layouts.main')

@section('title', 'Edit Peminjaman')

@section('content')
    <div class="container-fluid">
        <h1>Edit Peminjaman</h1>
        <form action="{{ route('borrowings.update', $borrowing->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="member_id" class="form-label">Anggota</label>
                <select class="form-control" id="member_id" name="member_id" required>
                    @foreach ($members as $member)
                        <option value="{{ $member->id }}" {{ $borrowing->member_id == $member->id ? 'selected' : '' }}>{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="book_id" class="form-label">Buku</label>
                <select class="form-control" id="book_id" name="book_id" required>
                    @foreach ($books as $book)
                        <option value="{{ $book->id }}" {{ $borrowing->book_id == $book->id ? 'selected' : '' }}>{{ $book->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="borrow_date" class="form-label">Tanggal Peminjaman</label>
                <input type="date" class="form-control" id="borrow_date" name="borrow_date" value="{{ $borrowing->borrow_date }}" required>
            </div>
            <div class="mb-3">
                <label for="return_date" class="form-label">Tanggal Pengembalian</label>
                <input type="date" class="form-control" id="return_date" name="return_date" value="{{ $borrowing->return_date }}">
            </div>
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
@endsection