@extends('layouts.main')

@section('title', 'Daftar Kunjungan')

@section('content')
<div class="container-fluid">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-times-circle"></i> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <!-- Tombol Tambah dan Export -->
    <div class="row mb-3">
        <div class="col-md-6">
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('create-kunjungan'))
            <a href="{{ route('admin.kunjungan.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Tambah Kunjungan
            </a>
            @endif
        </div>
        @if(auth()->user()->hasRole('admin'))
        <div class="col-md-6 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <form action="{{ route('admin.kunjungan.export.pdf') }}" method="GET" class="px-4 py-3">
                        <div class="form-group">
                            <label for="start_date_pdf">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" id="start_date_pdf">
                        </div>
                        <div class="form-group">
                            <label for="end_date_pdf">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" id="end_date_pdf">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Export</button>
                    </form>
                </div>
            </div>
            <div class="btn-group">
                <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-excel"></i> Export Excel
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <form action="{{ route('admin.kunjungan.export.excel') }}" method="GET" class="px-4 py-3">
                        <div class="form-group">
                            <label for="start_date_excel">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" id="start_date_excel">
                        </div>
                        <div class="form-group">
                            <label for="end_date_excel">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" id="end_date_excel">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Export</button>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Kunjungan</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable" id="tabelKunjungan">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Anggota</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>NIS/NIP</th>
                            <th>Tanggal Kunjungan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjungans as $kunjungan)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="badge badge-primary">{{ $kunjungan->anggota->kode_anggota }}</span>
                                </td>
                                <td>{{ $kunjungan->anggota->nama }}</td>
                                <td>
                                    @if($kunjungan->anggota->tipe == 'Siswa')
                                        <span class="badge badge-success">Siswa</span>
                                    @else
                                        <span class="badge badge-info">Guru</span>
                                    @endif
                                </td>
                                <td>
                                    @if($kunjungan->anggota->siswa)
                                        {{ $kunjungan->anggota->siswa->nis }}
                                    @elseif($kunjungan->anggota->guru)
                                        {{ $kunjungan->anggota->guru->nip }}
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d/m/Y') }}</td>
                                <td>{{ $kunjungan->keterangan ?: '-' }}</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-info btn-sm" title="Detail" data-toggle="modal" data-target="#detailModal_{{ $kunjungan->id }}">
                                            <i class="fas fa-eye"></i> Detail
                                        </button>
                                        @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('admin.kunjungan.edit', $kunjungan->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.kunjungan.destroy', $kunjungan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data kunjungan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                <i class="fas fa-trash"></i> Hapus
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">Tidak ada data kunjungan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail -->
@foreach($kunjungans as $kunjungan)
<div class="modal fade" id="detailModal_{{ $kunjungan->id }}" tabindex="-1" aria-labelledby="detailModalLabel_{{ $kunjungan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel_{{ $kunjungan->id }}">Detail Kunjungan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Anggota</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Kode Anggota</td>
                                <td>: <span class="badge badge-primary">{{ $kunjungan->anggota->kode_anggota }}</span></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $kunjungan->anggota->nama }}</td>
                            </tr>
                            <tr>
                                <td>Tipe</td>
                                <td>: 
                                    @if($kunjungan->anggota->tipe == 'Siswa')
                                        <span class="badge badge-success">Siswa</span>
                                    @else
                                        <span class="badge badge-info">Guru</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>NIS/NIP</td>
                                <td>: 
                                    @if($kunjungan->anggota->siswa)
                                        {{ $kunjungan->anggota->siswa->nis }}
                                    @elseif($kunjungan->anggota->guru)
                                        {{ $kunjungan->anggota->guru->nip }}
                                    @endif
                                </td>
                            </tr>
                            @if($kunjungan->anggota->siswa)
                            <tr>
                                <td>Kelas</td>
                                <td>: {{ $kunjungan->anggota->siswa->kelas->nama_kelas ?? '-' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Kunjungan</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Tanggal Kunjungan</td>
                                <td>: {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d/m/Y H:i') }}</td>
                            </tr>
                            <tr>
                                <td>Keterangan</td>
                                <td>: {{ $kunjungan->keterangan ?: '-' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection

@push('scripts')
<script>
$(function() {
    $('#tabelKunjungan').DataTable({
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
