@extends('layouts.main')

@section('title', 'Pengembalian')

@section('content')
    <div class="container-fluid">
        <h1>Pengembalian</h1>
        <a href="{{ route('book-returns.create') }}" class="btn btn-primary mb-3">Tambah Pengembalian</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Peminjaman</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Fine</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bookReturns as $bookReturn)
                    <tr>
                        <td>{{ $bookReturn->id }}</td>
                        <td>{{ $bookReturn->borrowing->book->title }} ({{ $bookReturn->borrowing->member->name }})</td>
                        <td>{{ $bookReturn->return_date }}</td>
                        <td>{{ $bookReturn->fine }}</td>
                        <td>
                            <a href="{{ route('book-returns.edit', $bookReturn->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('book-returns.destroy', $bookReturn->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection