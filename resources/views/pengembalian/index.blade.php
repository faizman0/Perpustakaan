@extends('layouts.main')

@section('title', 'Data Pengembalian')

@section('content')
<div class="container-fluid">
    @if(auth()->user()->hasRole('admin'))
    <div class="row mb-3">
        <div class="col-12 text-right">
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-file-pdf"></i> Export PDF
                </button>
                <div class="dropdown-menu dropdown-menu-right">
                    <form action="{{ route('admin.pengembalian.export.pdf') }}" method="GET" class="px-4 py-3">
                        <div class="form-group">
                            <label for="start_date_pdf">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" id="start_date_pdf" value="{{ request('start_date') }}">
                        </div>
                        <div class="form-group">
                            <label for="end_date_pdf">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" id="end_date_pdf" value="{{ request('end_date') }}">
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
                    <form action="{{ route('admin.pengembalian.export.excel') }}" method="GET" class="px-4 py-3">
                        <div class="form-group">
                            <label for="start_date_excel">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" id="start_date_excel" value="{{ request('start_date') }}">
                        </div>
                        <div class="form-group">
                            <label for="end_date_excel">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" id="end_date_excel" value="{{ request('end_date') }}">
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Export</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="pengembalianTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="aktif-tab" data-toggle="tab" href="#aktif" role="tab" aria-controls="aktif" aria-selected="true">
                        Peminjaman Aktif
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="riwayat-tab" data-toggle="tab" href="#riwayat" role="tab" aria-controls="riwayat" aria-selected="false">
                        Riwayat Pengembalian
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="pengembalianTabsContent">
                <!-- Peminjaman Aktif Tab -->
                <div class="tab-pane fade show active" id="aktif" role="tabpanel" aria-labelledby="aktif-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Anggota</th>
                                    <th>Peminjam</th>
                                    <th>Tipe</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Jatuh Tempo</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($peminjamanBelumKembali as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $p->anggota->kode_anggota }}</span>
                                        </td>
                                        <td>
                                            {{ $p->anggota->nama }}
                                            <br>
                                            <small class="text-muted">
                                                @if($p->anggota->siswa)
                                                    {{ $p->anggota->siswa->nis }}
                                                @elseif($p->anggota->guru)
                                                    {{ $p->anggota->guru->nip }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            @if($p->anggota->tipe == 'Siswa')
                                                <span class="badge badge-success">Siswa</span>
                                            @else
                                                <span class="badge badge-info">Guru</span>
                                            @endif
                                        </td>
                                        <td>{{ $p->buku->judul ?? '-' }}</td>
                                        <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(14)->format('d/m/Y') : '-' }}</td>    
                                        <td>
                                            {!! $p->status !!}
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-primary btn-sm" 
                                                    data-toggle="modal" 
                                                    data-target="#pengembalianModal{{ $p->id }}">
                                                <i class="fas fa-undo"></i> Kembalikan
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Tidak ada data peminjaman aktif</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Riwayat Pengembalian Tab -->
                <div class="tab-pane fade" id="riwayat" role="tabpanel" aria-labelledby="riwayat-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped datatable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Anggota</th>
                                    <th>Peminjam</th>
                                    <th>Tipe</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengembalians as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <span class="badge badge-primary">{{ $p->peminjaman->anggota->kode_anggota }}</span>
                                        </td>
                                        <td>
                                            {{ $p->peminjaman->anggota->nama }}
                                            <br>
                                            <small class="text-muted">
                                                @if($p->peminjaman->anggota->siswa)
                                                    {{ $p->peminjaman->anggota->siswa->nis }}
                                                @elseif($p->peminjaman->anggota->guru)
                                                    {{ $p->peminjaman->anggota->guru->nip }}
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            @if($p->peminjaman->anggota->tipe == 'Siswa')
                                                <span class="badge badge-success">Siswa</span>
                                            @else
                                                <span class="badge badge-info">Guru</span>
                                            @endif
                                        </td>
                                        <td>{{ $p->peminjaman->buku->judul ?? '-' }}</td>
                                        <td>{{ $p->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($p->peminjaman->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                                        <td>: {{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y H:i') }}</td>
                                        <td>
                                            {!! $p->status_detail !!}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailPengembalianModal{{ $p->id }}">
                                                    <i class="fas fa-eye"></i> Detail
                                                </button>
                                                @if(auth()->user()->hasRole('admin'))
                                                    <form action="{{ route('admin.pengembalian.destroy', $p->id) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i></i> Batalkan Pengembalian
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data pengembalian</td>
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

<!-- Modals -->
@foreach($peminjamanBelumKembali as $p)
    <div class="modal fade" id="pengembalianModal{{ $p->id }}" tabindex="-1" aria-labelledby="pengembalianModalLabel{{ $p->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pengembalianModalLabel{{ $p->id }}">Form Pengembalian Buku</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ auth()->user()->hasRole('admin') ? route('admin.pengembalian.store') : route('petugas.pengembalian.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="peminjaman_id" value="{{ $p->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Peminjam</label>
                            <p><strong>{{ $p->anggota->nama }}</strong> ({{ $p->anggota->kode_anggota }})</p>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Buku</label>
                            <p><strong>{{ $p->buku->judul }}</strong></p>
                        </div>

                        <div class="mb-3">
                            <label for="tanggal_kembali" class="form-label">Tanggal Pengembalian</label>
                            <input type="datetime-local" class="form-control" id="tanggal_kembali_display" value="{{ now('Asia/Jakarta')->format('Y-m-d\\TH:i') }}" disabled>
                            <input type="hidden" name="tanggal_kembali" value="{{ now('Asia/Jakarta')->format('Y-m-d\\TH:i') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

<!-- Modal Detail Pengembalian -->
@foreach($pengembalians as $p)
<div class="modal fade" id="detailPengembalianModal{{ $p->id }}" tabindex="-1" aria-labelledby="detailPengembalianModalLabel{{ $p->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detailPengembalianModalLabel{{ $p->id }}">Detail Pengembalian</h5>
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
                                <td>: <span class="badge badge-primary">{{ $p->peminjaman->anggota->kode_anggota }}</span></td>
                            </tr>
                            <tr>
                                <td>Nama</td>
                                <td>: {{ $p->peminjaman->anggota->nama }}</td>
                            </tr>
                            <tr>
                                <td>Tipe</td>
                                <td>: 
                                    @if($p->peminjaman->anggota->tipe == 'Siswa')
                                        <span class="badge badge-success">Siswa</span>
                                    @else
                                        <span class="badge badge-info">Guru</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>NIS/NIP</td>
                                <td>: 
                                    @if($p->peminjaman->anggota->siswa)
                                        {{ $p->peminjaman->anggota->siswa->nis }}
                                    @elseif($p->peminjaman->anggota->guru)
                                        {{ $p->peminjaman->anggota->guru->nip }}
                                    @endif
                                </td>
                            </tr>
                            @if($p->peminjaman->anggota->siswa)
                            <tr>
                                <td>Kelas</td>
                                <td>: {{ $p->peminjaman->anggota->siswa->kelas->nama_kelas ?? '-' }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="font-weight-bold">Informasi Buku</h6>
                        <table class="table table-bordered">
                            <tr>
                                <td>Judul</td>
                                <td>: {{ $p->peminjaman->buku->judul }}</td>
                            </tr>
                            <tr>
                                <td>Pengarang</td>
                                <td>: {{ $p->peminjaman->buku->pengarang }}</td>
                            </tr>
                            <tr>
                                <td>Kategori</td>
                                <td>: {{ $p->peminjaman->buku->kategori->nama }}</td>
                            </tr>
                            <tr>
                                <td>No. Klasifikasi</td>
                                <td>: {{ $p->peminjaman->buku->no_klasifikasi }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <hr>
                <h6 class="font-weight-bold">Informasi Pengembalian</h6>
                <table class="table table-borderless">
                    <tr>
                        <td width="200">Tanggal Pinjam</td>
                        <td>: {{ $p->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($p->peminjaman->tanggal_pinjam)->format('d M Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <td width="200">Tanggal Kembali</td>
                        <td>: {{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y H:i') : '-' }}</td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>: {!! $p->status_detail !!}</td>
                    </tr>
                    @if($p->keterangan)
                    <tr>
                        <td>Keterangan</td>
                        <td>: {{ $p->keterangan }}</td>
                    </tr>
                    @endif
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
    // Initialize both DataTables
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