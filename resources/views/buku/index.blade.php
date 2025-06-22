@extends('layouts.main')

@section('title', 'Buku')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Buku</h3>
                <div class="card-tools">
                    <div class="btn-group">
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('create-buku'))
                        <a href="{{ route('admin.buku.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Buku
                        </a>
                        @endif
                        @if(auth()->user()->hasRole('admin'))
                        <a href="{{ route('admin.buku.export.excel') }}" class="btn btn-success btn-sm ms-2">
                            <i class="fas fa-file-excel"></i> Export Excel
                        </a>
                        <a href="{{ route('admin.buku.export.pdf') }}" class="btn btn-danger btn-sm ms-2">
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
                <div class="table-responsive">
                    <table class="table table-bordered table-striped datatable" id="tabelBuku">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kode Buku</th>
                                <th>Judul</th>
                                <th>Kategori</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun Terbit</th>
                                <th>Stok</th>
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('edit-buku') || auth()->user()->hasPermission('delete-buku'))
                                <th width="15%">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bukus as $buku)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $buku->kode_buku }}</td>
                                    <td>{{ $buku->judul }}</td>
                                    <td>{{ $buku->kategori->nama }}</td>
                                    <td>{{ $buku->pengarang }}</td>
                                    <td>{{ $buku->penerbit }}</td>
                                    <td>{{ $buku->tahun_terbit }}</td>
                                    <td>{{ $buku->stok }}</td>
                                    
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('edit-buku') || auth()->user()->hasPermission('delete-buku'))
                                    <td>
                                        <div class="btn-group">
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('edit-buku'))
                                            <a href="{{ route('admin.buku.edit', $buku->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('delete-buku'))
                                            <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
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

            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
    @foreach($bukus as $buku)
    
    <!-- Modal Detail -->
    <div class="modal fade" id="detailModal{{ $buku->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $buku->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel{{ $buku->id }}">Detail Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Kategori</th>
                            <td>{{ $buku->kategori->nama }}</td>
                        </tr>
                        <tr>
                            <th width="30%">Judul</th>
                            <td>{{ $buku->judul }}</td>
                        </tr>
                        <tr>
                            <th>No. Inventaris</th>
                            <td>{{ $buku->no_inventaris }}</td>
                        </tr>
                        <tr>
                            <th>No. Klasifikasi</th>
                            <td>{{ $buku->no_klasifikasi }}</td>
                        </tr>
                        <tr>
                            <th>pengarang</th>
                            <td>{{ $buku->pengarang }}</td>
                        </tr>
                        <tr>
                            <th>Penerbit</th>
                            <td>{{ $buku->penerbit }}</td>
                        </tr>
                        <tr>
                            <th>Tahun</th>
                            <td>{{ $buku->tahun_terbit }}</td>
                        </tr>
                        <tr>
                            <th>Edisi</th>
                            <td>{{ $buku->edisi }}</td>
                        </tr>
                        <tr>
                            <th>ISBN</th>
                            <td>{{ $buku->isbn }}</td>
                        </tr>
                        <tr>
                            <th>Kolase</th>
                            <td>{{ $buku->kolase }}</td>
                        </tr>
                        <tr>
                            <th>Jumlah</th>
                            <td>{{ $buku->jumlah }}</td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td>{{ $buku->keterangan }}</td>
                        </tr>
                        
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Modal Import -->
    @if(auth()->user()->hasRole('admin'))
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.buku.import.excel') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Data Buku</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    @if(session('import_errors'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h5><i class="icon fas fa-ban"></i> Error Import!</h5>
                            <ul>
                                @foreach(session('import_errors') as $error)
                                    <li>
                                        Baris {{ $error->row() }}:
                                        {{ implode(', ', $error->errors()) }}
                                        <br>
                                        <small>Nilai: {{ json_encode($error->values()) }}</small>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">×</button>
                            <h5><i class="icon fas fa-ban"></i> Error!</h5>
                            {{ session('error') }}
                        </div>
                    @endif
                        <div class="form-group">
                            <label for="importFile">Pilih File Excel</label>
                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="importFile" name="file" required accept=".xlsx,.xls">
                                    <label class="custom-file-label" for="importFile">Pilih file</label>
                                </div>
                            </div>
                            <small class="text-muted">Format file harus .xlsx atau .xls (Maks. 2MB)</small>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('admin.buku.template.download') }}" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-download"></i> Download Template
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Import Data</button>
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

        // File input name display
        $('.custom-file-input').on('change', function() {
            let fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').addClass("selected").html(fileName);
        });

        // Delete button handler
        $('.delete-btn').click(function() {
            let bukuId = $(this).data('id');
            if (confirm('Apakah Anda yakin ingin menghapus buku ini?')) {
                $('#deleteForm').attr('action', '/buku/' + bukuId).submit();
            }
        });
    });
</script>
@endpush
