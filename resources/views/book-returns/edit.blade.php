@extends('layouts.main')

@section('title', 'Edit Pengembalian')

@section('content')
    <div class="container-fluid">
        <h1>Edit Pengembalian</h1>
        <form action="{{ route('book-returns.update', $bookReturn->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="borrowing_id" class="form-label">Peminjaman</label>
                <select class="form-control" id="borrowing_id" name="borrowing_id" required>
                    @foreach ($borrowings as $borrowing)
                        <option value="{{ $borrowing->id }}" {{ $bookReturn->borrowing_id == $borrowing->id ? 'selected' : '' }}>{{ $borrowing->book->title }} ({{ $borrowing->member->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="return_date" class="form-label">Tanggal Pengembalian</label>
                <input type="date" class="form-control" id="return_date" name="return_date" value="{{ $bookReturn->return_date }}" required>
            </div>
            <div class="mb-3">
                <label for="fine" class="form-label">Fine</label>
                <input type="number" class="form-control" id="fine" name="fine" value="{{ $bookReturn->fine }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>
    </div>
@endsection