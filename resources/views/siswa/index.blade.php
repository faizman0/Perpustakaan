@extends('layouts.main')

@section('title', 'Siswa')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Siswa</h3>
            <div class="card-tools">
                <div class="btn-group">
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.siswa.create') : route('petugas.siswa.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Tambah Siswa
                    </a>
                    @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.siswa.export.excel') }}" class="btn btn-success btn-sm ms-2">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    <a href="{{ route('admin.siswa.export.pdf') }}" class="btn btn-danger btn-sm ms-2">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <button type="button" class="btn btn-info btn-sm ms-2" data-bs-toggle="modal" data-bs-target="#importModal">
                        <i class="fas fa-file-import"></i> Import
                    </button>
                    @endif
                </div>
            </div>
        </div>
        <!-- /.card-header -->
        <div class="card-body"> 
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {!! nl2br(e(session('warning'))) !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <table class="table table-bordered table-striped datatable" id="tabelSiswa">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>NIS</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswas as $index => $siswa)
                        <tr>
                            <td>{{ $index + 1 }}</td> 
                            <td>{{ $siswa->nama }}</td>
                            <td>{{ $siswa->nis}}</td>
                            <td>{{ $siswa->jenis_kelamin}}</td>
                            <td>{{ $siswa->kelas->nama_kelas }}</td>
                            <td class="text-center">
                                @if(auth()->user()->hasRole('admin'))
                                <a href="{{ route('admin.siswa.edit', $siswa->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>

<!-- Import Modal -->
@if(auth()->user()->hasRole('admin'))
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.siswa.import.excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Petunjuk Import Data Siswa:</h5>
                        <ol class="mb-0">
                            <li>Download template Excel yang telah disediakan</li>
                            <li>Isi data sesuai dengan format yang ada di template</li>
                            <li>Pastikan semua kolom wajib terisi (Nama, NIS, Jenis Kelamin, Kelas)</li>
                            <li>NIS harus berupa angka dan bersifat unik</li>
                            <li>Jenis Kelamin harus diisi dengan "Laki-laki" atau "Perempuan"</li>
                            <li>Upload file Excel yang telah diisi</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <label for="importFile" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control" id="importFile" name="file" required accept=".xlsx,.xls">
                        <div class="form-text">Format file harus .xlsx atau .xls (Maks. 2MB)</div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.siswa.template.download') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<!-- Form Hapus tersembunyi -->
<form id="deleteForm" method="POST" style="display: none;">
        @csrf
        @method('DELETE')
    </form>
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
            }
        });

        // Auto close alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
</script>
@endpush
