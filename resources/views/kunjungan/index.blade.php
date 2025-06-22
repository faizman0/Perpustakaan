@extends('layouts.main')

@section('title', 'Daftar Kunjungan')

@section('content')
<div class="container-fluid">
    <!-- Tombol Tambah dan Export -->
    <div class="mb-3">
        <a href="{{ auth()->user()->hasRole('admin') ? route('admin.kunjungan.create') : route('petugas.kunjungan.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus"></i> Tambah Kunjungan
        </a>
        @if(auth()->user()->hasRole('admin'))
        <div class="btn-group float-end">
            <form action="{{ route('admin.kunjungan.export.pdf') }}" method="GET" class="d-inline">
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control form-control-sm" placeholder="Tanggal Mulai">
                    <input type="date" name="end_date" class="form-control form-control-sm" placeholder="Tanggal Akhir">
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </button>
                </div>
            </form>
            <form action="{{ route('admin.kunjungan.export.excel') }}" method="GET" class="d-inline ms-2">
                <div class="input-group">
                    <input type="date" name="start_date" class="form-control form-control-sm" placeholder="Tanggal Mulai">
                    <input type="date" name="end_date" class="form-control form-control-sm" placeholder="Tanggal Akhir">
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </button>
                </div>
            </form>
        </div>
        @endif
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="siswa-tab" data-bs-toggle="tab" href="#siswa" role="tab" aria-controls="siswa" aria-selected="true">
                        <i class="fas fa-user-graduate"></i> Kunjungan Siswa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="guru-tab" data-bs-toggle="tab" href="#guru" role="tab" aria-controls="guru" aria-selected="false">
                        <i class="fas fa-chalkboard-teacher"></i> Kunjungan Guru
                    </a>
                </li>
            </ul>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Success!</h5>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading">Error!</h5>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="tab-content" id="kunjunganTabContent">
                <!-- Tab Kunjungan Siswa -->
                <div class="tab-pane fade show active" id="siswa" role="tabpanel" aria-labelledby="siswa-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable" id="tabelKunjunganSiswa">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Tanggal Kunjungan</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kunjunganSiswa as $index => $kunjungan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $kunjungan->siswa->nis }}</td>
                                        <td>{{ $kunjungan->siswa->nama }}</td>
                                        <td>{{ $kunjungan->siswa->kelas->nama_kelas }}</td>
                                        <td>{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d/m/Y') }}</td>
                                        <td>{{ $kunjungan->keterangan ?: '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm" title="Detail" data-bs-toggle="modal" data-bs-target="#detailModalSiswa_{{ $kunjungan->id }}">
                                                    <i class="bi bi-eye"></i> Detail
                                                </button>
                                                @if(auth()->user()->hasRole('admin'))
                                                <a href="{{ route('admin.kunjungan.edit', $kunjungan->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.kunjungan.destroy', $kunjungan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data kunjungan siswa</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Tab Kunjungan Guru -->
                <div class="tab-pane fade" id="guru" role="tabpanel" aria-labelledby="guru-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable" id="tabelKunjunganGuru">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama Guru</th>
                                    <th>Tanggal Kunjungan</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kunjunganGuru as $index => $kunjungan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $kunjungan->guru->nip }}</td>
                                        <td>{{ $kunjungan->guru->nama }}</td>
                                        <td>{{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d/m/Y') }}</td>
                                        <td>{{ $kunjungan->keterangan ?: '-' }}</td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm" title="Detail" data-bs-toggle="modal" data-bs-target="#detailModalGuru_{{ $kunjungan->id }}">
                                                    <i class="bi bi-eye"></i> Detail
                                                </button>
                                                @if(auth()->user()->hasRole('admin'))
                                                <a href="{{ route('admin.kunjungan.edit', $kunjungan->id) }}" class="btn btn-warning btn-sm" title="Edit">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('admin.kunjungan.destroy', $kunjungan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Tidak ada data kunjungan guru</td>
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

<!-- Modal Detail Siswa -->
@foreach($kunjunganSiswa as $kunjungan)
<div class="modal fade" id="detailModalSiswa_{{ $kunjungan->id }}" tabindex="-1" aria-labelledby="detailModalLabelSiswa_{{ $kunjungan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabelSiswa_{{ $kunjungan->id }}">Detail Kunjungan Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Siswa</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $kunjungan->siswa->nama }}</td>
                            </tr>
                            <tr>
                                <td>NIS</td>
                                <td>: {{ $kunjungan->siswa->nis }}</td>
                            </tr>
                            <tr>
                                <td>Kelas</td>
                                <td>: {{ $kunjungan->siswa->kelas->nama_kelas }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h6 class="font-weight-bold">Informasi Kunjungan</h6>
                <table class="table table-borderless">
                    <tr>
                        <td width="200">Tanggal Kunjungan</td>
                        <td>: {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>: {{ $kunjungan->keterangan ?: '-' }}</td>
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

<!-- Modal Detail Guru -->
@foreach($kunjunganGuru as $kunjungan)
<div class="modal fade" id="detailModalGuru_{{ $kunjungan->id }}" tabindex="-1" aria-labelledby="detailModalLabelGuru_{{ $kunjungan->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabelGuru_{{ $kunjungan->id }}">Detail Kunjungan Guru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Guru</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $kunjungan->guru->nama }}</td>
                            </tr>
                            <tr>
                                <td>NIP</td>
                                <td>: {{ $kunjungan->guru->nip }}</td>
                            </tr>
                            <tr>
                                <td>Bidang Studi</td>
                                <td>: {{ $kunjungan->guru->bidang_studi }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h6 class="font-weight-bold">Informasi Kunjungan</h6>
                <table class="table table-borderless">
                    <tr>
                        <td width="200">Tanggal Kunjungan</td>
                        <td>: {{ \Carbon\Carbon::parse($kunjungan->tanggal_kunjungan)->format('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td>Keterangan</td>
                        <td>: {{ $kunjungan->keterangan ?: '-' }}</td>
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
$(document).ready(function() {
    // Initialize DataTables
    $('#tabelKunjunganSiswa, #tabelKunjunganGuru').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });
});
</script>
@endpush
