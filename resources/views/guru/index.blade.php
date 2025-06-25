@extends('layouts.main')

@section('title', 'Guru')

@section('content')
<div class="container-fluid">
    <div class="row mb-3">
        <div class="col-12">
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('create-guru'))
            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.guru.create') : route('petugas.guru.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Guru
            </a>
            @endif
            @if(auth()->user()->hasRole('admin'))
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importModal">
                <i class="fas fa-file-import"></i> Import
            </button>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Guru</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <table class="table table-bordered table-striped datatable" id="tabelGuru">
                <thead>
                    <tr>
                        <th width="5%">No</th>
                        <th>Nama</th>
                        <th>NIP</th>
                        <th>Jenis Kelamin</th>
                        <th width="20%" class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($gurus as $guru)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $guru->nama }}</td>
                            <td>{{ $guru->nip }}</td>
                            <td>{{ $guru->jenis_kelamin }}</td>
                            <td class="text-center">
                                <div class="btn-group">
                                    @if(!$guru->anggota)
                                        <form action="{{ auth()->user()->hasRole('admin') ? route('admin.anggota.store') : route('petugas.anggota.store') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="tipe" value="Guru">
                                            <input type="hidden" name="id" value="{{ $guru->id }}">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-user-plus"></i> Jadikan Anggota
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.guru.edit', $guru->id) : route('petugas.guru.edit', $guru->id) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    @if(auth()->user()->hasRole('admin'))
                                    <form action="{{ route('admin.guru.destroy', $guru->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data guru ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                    @endif
                                </div>
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
                <h5 class="modal-title" id="importModalLabel">Import Data Guru</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('admin.guru.import.excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle"></i> Petunjuk Import Data Guru:</h5>
                        <ol class="mb-0">
                            <li>Download template Excel yang telah disediakan</li>
                            <li>Isi data sesuai dengan format yang ada di template</li>
                            <li>Pastikan semua kolom wajib terisi (Nama, NIP, Jenis Kelamin)</li>
                            <li>NIP harus berupa angka dan bersifat unik</li>
                            <li>Jenis Kelamin harus diisi dengan "Laki-laki" atau "Perempuan" (bisa juga "L" atau "P")</li>
                            <li>Upload file Excel yang telah diisi</li>
                        </ol>
                    </div>

                    <div class="mb-3">
                        <label for="importFile" class="form-label">Pilih File Excel</label>
                        <input type="file" class="form-control" id="importFile" name="file" required accept=".xlsx,.xls">
                        <div class="form-text">Format file harus .xlsx atau .xls (Maks. 2MB)</div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('admin.guru.template.download') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                    </div>
                </div>      
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Import Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

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
