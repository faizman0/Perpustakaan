@extends('layouts.main')

@section('title', 'Buku')

@section('content')
    <div class="container-fluid">
        <div class="row mb-3">
            <div class="col-12">
                @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('create-buku'))
                <a href="{{ route('admin.buku.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Buku
                </a>
                @endif
                @if(auth()->user()->hasRole('admin'))
                <a href="{{ route('admin.buku.export.excel') }}" class="btn btn-success">
                    <i class="fas fa-file-excel"></i> Export Excel
                </a>
                <a href="{{ route('admin.buku.export.pdf') }}" class="btn btn-danger">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </a>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#importModal">
                    <i class="fas fa-file-import"></i> Import
                </button>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Buku</h3>
            </div>
            <!-- /.card-header -->

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped datatable" id="tabelBuku">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Kategori</th>
                                <th>No. Inventaris</th>
                                <th>Judul</th>
                                <th>Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun Terbit</th>
                                <th>Jumlah</th>
                                @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('petugas') || auth()->user()->hasPermission('edit-buku') || auth()->user()->hasPermission('delete-buku'))
                                <th width="20%" class="text-center">Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($bukus as $buku)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $buku->kategori->nama }}</td>
                                    <td>{{ $buku->no_inventaris }}</td>
                                    <td>{{ $buku->judul }}</td>
                                    <td>{{ $buku->pengarang }}</td>
                                    <td>{{ $buku->penerbit }}</td>
                                    <td>{{ $buku->tahun_terbit }}</td>
                                    <td>{{ $buku->jumlah }}</td>
                                    
                                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('petugas') || auth()->user()->hasPermission('edit-buku') || auth()->user()->hasPermission('delete-buku'))
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal{{ $buku->id }}">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('edit-buku'))
                                            <a href="{{ route('admin.buku.edit', $buku->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            @endif
                                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('delete-buku'))
                                            <form action="{{ route('admin.buku.destroy', $buku->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data buku ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i> Hapus
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Import Modal -->
    @if(auth()->user()->hasRole('admin'))
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.buku.import.excel') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle"></i> Petunjuk Import Data Buku:</h5>
                            <ol class="mb-0">
                                <li>Download template Excel yang telah disediakan</li>
                                <li>Isi data sesuai dengan format yang ada di template</li>
                                <li>Pastikan kolom(Kategori, Judul, No. Inventaris, No. Klasifikasi, Pengarang, Penerbit, Tahun Terbit, ISBN, dan Jumlah Buku) wajib terisi</li>
                                <li>Upload file Excel yang telah diisi</li>
                            </ol>
                        </div>
                        <div class="mb-3">
                            <label for="importFile" class="form-label">Pilih File Excel</label>
                            <input type="file" class="form-control" id="importFile" name="file" required accept=".xlsx,.xls">
                            <div class="form-text">Format file harus .xlsx atau .xls (Maks. 2MB)</div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('admin.buku.template.download') }}" class="btn btn-outline-success btn-sm">
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
