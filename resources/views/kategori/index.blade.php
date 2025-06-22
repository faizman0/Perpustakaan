@extends('layouts.main')

@section('title', 'Kategori Buku')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Kategori Buku</h3>
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('create-kategori'))
            <div class="card-tools">
                <a href="{{ route('admin.kategori.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Tambah Kategori
                </a>
            </div>
            @endif
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered table-striped datatable" id="tabelKategori">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Kategori ID</th>
                        <th>Nama Kategori</th>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('edit-kategori') || auth()->user()->hasPermission('delete-kategori'))
                        <th width="15%">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($kategoris as $kategori)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $kategori->kategori_id }}</td>
                        <td>{{ $kategori->nama }}</td>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('edit-kategori') || auth()->user()->hasPermission('delete-kategori'))
                        <td>
                            <div class="btn-group">
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('edit-kategori'))
                                <a href="{{ route('admin.kategori.edit', $kategori->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('delete-kategori'))
                                <form action="{{ route('admin.kategori.destroy', $kategori->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
@endsection

@push('scripts')
<script>
    $(function () {
        // Initialize DataTable
        $('.datatable').DataTable({
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Indonesian.json"
            },
            "columnDefs": [
                { "orderable": false, "targets": [2] } // Disable sorting for action column
            ]
        });
    });
</script>
@endpush
