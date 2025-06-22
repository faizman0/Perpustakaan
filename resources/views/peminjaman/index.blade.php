@extends('layouts.main')

@section('title', 'Peminjaman')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Peminjaman</h3>
            <div class="card-tools">
                <div class="btn-group">
                    @if(auth()->user()->hasRole('admin'))
                    <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Tambah Peminjaman
                    </a>
                    <a href="{{ route('admin.peminjaman.export.pdf') }}" class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('admin.peminjaman.export.excel') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                    @elseif(auth()->user()->hasRole('petugas'))
                    <a href="{{ route('petugas.peminjaman.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Tambah Peminjaman
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card-body">
            <ul class="nav nav-tabs" id="peminjamanTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="siswa-tab" data-bs-toggle="tab" href="#siswa" role="tab">
                        <i class="fas fa-user-graduate"></i> Peminjaman Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="guru-tab" data-bs-toggle="tab" href="#guru" role="tab">
                        <i class="fas fa-chalkboard-teacher"></i> Peminjaman Guru
                    </a>
                </li>
            </ul>

            <div class="tab-content mt-3" id="peminjamanTabContent">
                <!-- Tab Peminjaman Siswa -->
                <div class="tab-pane fade show active" id="siswa" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable" id="tabelPeminjamanSiswa">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Judul Buku</th>
                                    <th>Kategori</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamanSiswa as $index => $peminjaman)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $peminjaman->siswa->nis }}</td>
                                    <td>{{ $peminjaman->siswa->nama }}</td>
                                    <td>{{ $peminjaman->siswa->kelas->nama_kelas }}</td>
                                    <td>{{ $peminjaman->buku->judul }}</td>
                                    <td>{{ $peminjaman->buku->kategori->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($peminjaman->tanggal_kembali)
                                            <span class="badge badge-success">Dikembalikan</span>
                                        @else
                                            <span class="badge badge-warning">Dipinjam</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm" title="Detail" data-bs-toggle="modal" data-bs-target="#detailModalSiswa_{{ $peminjaman->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('admin.peminjaman.edit', $peminjaman) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.peminjaman.destroy', $peminjaman) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="text-center">Tidak ada data peminjaman siswa</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Peminjaman Guru -->
                <div class="tab-pane fade" id="guru" role="tabpanel">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable" id="tabelPeminjamanGuru">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama Guru</th>
                                    <th>Judul Buku</th>
                                    <th>Kategori</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamanGuru as $index => $peminjaman)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $peminjaman->guru->nip }}</td>
                                    <td>{{ $peminjaman->guru->nama }}</td>
                                    <td>{{ $peminjaman->buku->judul }}</td>
                                    <td>{{ $peminjaman->buku->kategori->nama }}</td>
                                    <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td>{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td>
                                    <td>
                                        @if($peminjaman->tanggal_kembali)
                                            <span class="badge badge-success">Dikembalikan</span>
                                        @else
                                            <span class="badge badge-warning">Dipinjam</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-info btn-sm" title="Detail" data-bs-toggle="modal" data-bs-target="#detailModalGuru_{{ $peminjaman->id }}">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if(auth()->user()->hasRole('admin'))
                                            <a href="{{ route('admin.peminjaman.edit', $peminjaman) }}" class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.peminjaman.destroy', $peminjaman) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada data peminjaman guru</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($peminjamanSiswa as $peminjaman)
<!-- Modal Detail Siswa -->
<div class="modal fade" id="detailModalSiswa_{{ $peminjaman->id }}" tabindex="-1" aria-labelledby="detailModalLabelSiswa_{{ $peminjaman->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabelSiswa_{{ $peminjaman->id }}">Detail Peminjaman Siswa</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Buku</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Judul</td>
                                <td>: {{ $peminjaman->buku->judul }}</td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>: {{ $peminjaman->buku->kategori->nama }}</td>
                            </tr>
                            <tr>
                                <td>No. Inventaris</td>
                                <td>: {{ $peminjaman->buku->no_inventaris }}</td>
                            </tr>
                            <tr>
                                <td>No. Klasifikasi</td>
                                <td>: {{ $peminjaman->buku->no_klasifikasi }}</td>
                            </tr>
                            <tr>
                                <td>Pengarang</td>
                                <td>: {{ $peminjaman->buku->pengarang }}</td>
                            </tr>
                            <tr>
                                <td>Penerbit</td>
                                <td>: {{ $peminjaman->buku->penerbit }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Siswa</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $peminjaman->siswa->nama }}</td>
                            </tr>
                            <tr>
                                <td>NIS</td>
                                <td>: {{ $peminjaman->siswa->nis }}</td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>: {{ $peminjaman->siswa->kelas->nama_kelas }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h6 class="font-weight-bold">Informasi Peminjaman</h6>
                <table class="table table-borderless">
                    <tr>
                        <td width="200">Tanggal Pinjam</td>
                        <td>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Kembali</td>
                        <td>: {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: {{ $peminjaman->tanggal_kembali ? 'Dikembalikan' : 'Dipinjam' }}</td>
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

@foreach($peminjamanGuru as $peminjaman)
<!-- Modal Detail Guru -->
<div class="modal fade" id="detailModalGuru_{{ $peminjaman->id }}" tabindex="-1" aria-labelledby="detailModalLabelGuru_{{ $peminjaman->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabelGuru_{{ $peminjaman->id }}">Detail Peminjaman Guru</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Buku</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Judul</td>
                                <td>: {{ $peminjaman->buku->judul }}</td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>: {{ $peminjaman->buku->kategori->nama }}</td>
                            </tr>
                            <tr>
                                <td>No. Inventaris</td>
                                <td>: {{ $peminjaman->buku->no_inventaris }}</td>
                            </tr>
                            <tr>
                                <td>No. Klasifikasi</td>
                                <td>: {{ $peminjaman->buku->no_klasifikasi }}</td>
                            </tr>
                            <tr>
                                <td>Pengarang</td>
                                <td>: {{ $peminjaman->buku->pengarang }}</td>
                            </tr>
                            <tr>
                                <td>Penerbit</td>
                                <td>: {{ $peminjaman->buku->penerbit }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Guru</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $peminjaman->guru->nama }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>: {{ $peminjaman->guru->nip }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h6 class="font-weight-bold">Informasi Peminjaman</h6>
                <table class="table table-borderless">
                    <tr>
                        <td width="200">Tanggal Pinjam</td>
                        <td>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Kembali</td>
                        <td>: {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: {{ $peminjaman->tanggal_kembali ? 'Dikembalikan' : 'Dipinjam' }}</td>
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
    });
</script>
@endpush