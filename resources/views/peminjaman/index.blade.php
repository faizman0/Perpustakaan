@extends('layouts.main')

@section('title', 'Peminjaman')

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

    <div class="row mb-3">
        <div class="col-12">
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasPermission('create-peminjaman'))
                <a href="{{ route('admin.peminjaman.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Peminjaman
                </a>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Daftar Peminjaman</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped datatable" id="tabelPeminjaman">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Anggota</th>
                            <th>Nama</th>
                            <th>Tipe</th>
                            <th>NIS/NIP</th>
                            <th>Judul Buku</th>
                            <th>Kategori</th>
                            <th>Tanggal Pinjam</th>
                            <th>Tanggal Kembali</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($peminjamans as $index => $peminjaman)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span class="badge badge-primary">{{ $peminjaman->anggota->kode_anggota }}</span>
                            </td>
                            <td>{{ $peminjaman->anggota->nama }}</td>
                            <td>
                                @if($peminjaman->anggota->tipe == 'Siswa')
                                    <span class="badge badge-success">Siswa</span>
                                @else
                                    <span class="badge badge-info">Guru</span>
                                @endif
                            </td>
                            <td>
                                @if($peminjaman->anggota->siswa)
                                    {{ $peminjaman->anggota->siswa->nis }}
                                @elseif($peminjaman->anggota->guru)
                                    {{ $peminjaman->anggota->guru->nip }}
                                @endif
                            </td>
                            <td>{{ $peminjaman->buku->judul }}</td>
                            <td>{{ $peminjaman->buku->kategori->nama }}</td>
                            <td>{{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>{{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y') : '-' }}</td>
                            <td>
                                {!! $peminjaman->status !!}
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-info btn-sm" title="Detail" data-toggle="modal" data-target="#detailModal_{{ $peminjaman->id }}">
                                        <i class="fas fa-eye"></i> Detail
                                    </button>
                                    @if(auth()->user()->hasRole('admin'))
                                    <a href="{{ route('admin.peminjaman.edit', $peminjaman) }}" class="btn btn-warning btn-sm" title="Edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.peminjaman.destroy', $peminjaman) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.peminjaman.export.bukti.pdf', ['peminjaman' => $peminjaman->id]) }}" class="btn btn-danger btn-sm" title="Export Bukti PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i> Bukti
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada data peminjaman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach($peminjamans as $peminjaman)
<!-- Modal Detail -->
<div class="modal fade" id="detailModal_{{ $peminjaman->id }}" tabindex="-1" aria-labelledby="detailModalLabel_{{ $peminjaman->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailModalLabel_{{ $peminjaman->id }}">Detail Peminjaman</h5>
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
                                <td>: <span class="badge badge-primary">{{ $peminjaman->anggota->kode_anggota }}</span></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $peminjaman->anggota->nama }}</td>
                            </tr>
                            <tr>
                                <td>Tipe</td>
                                <td>: 
                                    @if($peminjaman->anggota->tipe == 'Siswa')
                                        <span class="badge badge-success">Siswa</span>
                                    @else
                                        <span class="badge badge-info">Guru</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>NIS/NIP</td>
                                <td>: 
                                    @if($peminjaman->anggota->siswa)
                                        {{ $peminjaman->anggota->siswa->nis }}
                                    @elseif($peminjaman->anggota->guru)
                                        {{ $peminjaman->anggota->guru->nip }}
                                    @endif
                                </td>
                            </tr>
                            @if($peminjaman->anggota->siswa)
                            <tr>
                                <td>Kelas</td>
                                <td>: {{ $peminjaman->anggota->siswa->kelas->nama_kelas ?? '-' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Buku</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Judul</td>
                                <td>: {{ $peminjaman->buku->judul }}</td>
                            </tr>
                            <tr>
                                <td>Pengarang</td>
                                <td>: {{ $peminjaman->buku->pengarang }}</td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>: {{ $peminjaman->buku->kategori->nama }}</td>
                            </tr>
                            <tr>
                                <td>No. Klasifikasi</td>
                                <td>: {{ $peminjaman->buku->no_klasifikasi }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h6 class="font-weight-bold">Informasi Peminjaman</h6>
                <table class="table table-borderless">
                    <tr>
                        <td width="200">Tanggal Peminjaman</td>
                        <td>: {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d M Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td width="200">Tanggal Pengembalian</td>
                        <td>: {{ $peminjaman->tanggal_kembali ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali)->format('d M Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <td width="200">Status</td>
                        <td>: {!! $peminjaman->status !!}</td>
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

@endsection

@push('scripts')
<script>
$(function() {
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