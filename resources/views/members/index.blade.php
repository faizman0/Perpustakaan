@extends('layouts.main')

@section('title', 'Anggota')

@section('content')
<div class="container-fluid">
    <h2>Anggota</h2>
    <div class="mb-3">
        <a href="{{ route('members.export.excel') }}" class="btn btn-success">
            <i class="fas fa-file-excel"></i> Export Excel
        </a>
        <a href="{{ route('members.export.pdf') }}" class="btn btn-danger">
            <i class="fas fa-file-pdf"></i> Export PDF
        </a>
        
        <!-- Tombol Trigger Modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="fas fa-file-import"></i> Import Excel
        </button>

        
    </div>

    <!-- Modal Import -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Anggota</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('members.import.excel') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="importFile" class="form-label">Pilih File Excel</label>
                            <input class="form-control" type="file" id="importFile" name="file" required accept=".xlsx,.xls">
                            <div class="form-text">Format file harus .xlsx atau .xls (Maks. 2MB)</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <a href="{{ route('members.create') }}" class="btn btn-primary mb-3">Tambah Anggota</a>
    <table class="table table-bordered" id="membersTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Kelas</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($members as $member)
                <tr>
                    <td>{{ $member->id }}</td>
                    <td>{{ $member->name }}</td>
                    <td>{{ $member->nis }}</td>
                    <td>{{ $member->class }}</td>
                    <td>
                        <a href="{{ route('members.edit', $member->id) }}" class="btn btn-warning">Edit</a>
                        <form action="{{ route('members.destroy', $member->id) }}" method="POST" style="display:inline;">
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