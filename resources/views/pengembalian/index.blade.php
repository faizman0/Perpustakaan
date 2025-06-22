@extends('layouts.main')

@section('title', 'Data Pengembalian')

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
                                        <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            {!! $p->status_detail !!}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                @if(auth()->user()->hasRole('admin'))
                                                    <a href="{{ route('admin.pengembalian.edit', $p->id) }}" 
                                                       class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i> Edit
                                                    </a>
                                                    <form action="{{ route('admin.pengembalian.destroy', $p->id) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i> Hapus
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.pengembalian.export.bukti.pdf', ['pengembalian' => $p->id]) }}" class="btn btn-danger btn-sm" title="Export Bukti PDF" target="_blank">
                                                        <i class="fas fa-file-pdf"></i> Bukti
                                                    </a>
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
                <form action="{{ route('admin.pengembalian.store') }}" method="POST">
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
                            <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" value="{{ date('Y-m-d') }}" required>
                        </div>

                        @php
                            $jatuhTempo = \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(14);
                            $denda = 0;
                            if(now()->gt($jatuhTempo)) {
                                $keterlambatan = now()->diffInDays($jatuhTempo);
                                $denda = $keterlambatan * 1000; // Contoh denda Rp1.000 per hari
                            }
                        @endphp
                        
                        @if($denda > 0)
                        <div class="alert alert-warning">
                            <p class="mb-0"><strong>Denda Keterlambatan:</strong></p>
                            <p class="mb-0">Terlambat: {{ now()->diffInDays($jatuhTempo) }} hari</p>
                            <p class="mb-0">Denda: Rp {{ number_format($denda, 0, ',', '.') }}</p>
                            <input type="hidden" name="denda" value="{{ $denda }}">
                        </div>
                        @endif
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