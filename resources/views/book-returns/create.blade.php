@extends('layouts.main')

@section('title', 'Tambah Pengembalian')

@section('content')
    <div class="container-fluid">
        <h1>Tambah Pengembalian</h1>
        <form action="{{ route('book-returns.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="borrowing_id" class="form-label">Pengembalian</label>
                <select class="form-control" id="borrowing_id" name="borrowing_id" required>
                    @foreach ($borrowings as $borrowing)
                        <option value="{{ $borrowing->id }}">{{ $borrowing->book->title }} ({{ $borrowing->member->name }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="return_date" class="form-label">Tanggal Pengembalian</label>
                <input type="date" class="form-control" id="return_date" name="return_date" required>
            </div>
            <div class="mb-3">
                <label for="fine" class="form-label">Fine</label>
                <input type="number" class="form-control" id="fine" name="fine" value="0" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection