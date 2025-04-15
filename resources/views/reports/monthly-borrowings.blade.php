@extends('layouts.main')

@section('title', 'Laporan')

@section('content')
<div class="container-fluid">
    <h2>Laporan</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Jumlah Peminjaman</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($borrowings as $borrowing)
                <tr>
                    <td>{{ $borrowing->year }}-{{ $borrowing->month }}</td>
                    <td>{{ $borrowing->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection