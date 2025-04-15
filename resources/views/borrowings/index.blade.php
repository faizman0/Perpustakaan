@extends('layouts.main')

@section('title', 'Peminjaman')

@section('content')
    <div class="container-fluid">
        <h1>Peminjaman</h1>
        <a href="{{ route('borrowings.create') }}" class="btn btn-primary mb-3">Tambah Peminjaman</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Anggota</th>
                    <th>Buku</th>
                    <th>Tanggal Peminjaman</th>
                    <th>Tanggal Pengembalian</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($borrowings as $borrowing)
                    <tr>
                        <td>{{ $borrowing->id }}</td>
                        <td>{{ $borrowing->member->name }}</td>
                        <td>{{ $borrowing->book->title }}</td>
                        <td>{{ $borrowing->borrow_date }}</td>
                        <td>{{ $borrowing->return_date ?? 'Not Returned' }}</td>
                        <!-- <td>{{ $borrowing->return_date ?? 'Belum Dikembalikan' }}</td> -->
                        <td>
                            @if (!$borrowing->return_date)
                                <form action="{{ route('borrowings.return', $borrowing->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="btn btn-success">Kembalikan</button>
                                </form>
                            @else
                                <span class="text-success">Sudah Dikembalikan</span>
                            @endif
                            
                            <a href="{{ route('borrowings.edit', $borrowing->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('borrowings.destroy', $borrowing->id) }}" method="POST" style="display:inline;">
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