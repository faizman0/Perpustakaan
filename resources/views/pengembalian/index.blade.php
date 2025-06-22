@extends('layouts.main')

@section('title', 'Data Pengembalian')

@section('content')
<div class="container-fluid">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="pengembalianTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="aktif-tab" data-bs-toggle="tab" data-bs-target="#aktif" type="button" role="tab" aria-controls="aktif" aria-selected="true">
                        Peminjaman Aktif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="riwayat-tab" data-bs-toggle="tab" data-bs-target="#riwayat" type="button" role="tab" aria-controls="riwayat" aria-selected="false">
                        Riwayat Pengembalian
                    </button>
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
                                    <th>Peminjam</th>
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
                                            @if($p->siswa_id)
                                                {{ $p->siswa->nama ?? '-' }} ({{ $p->siswa->nis ?? '-' }})
                                                <br>
                                                <small class="text-muted">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</small>
                                            @else
                                                {{ $p->guru->nama ?? '-' }} ({{ $p->guru->nip ?? '-' }})
                                            @endif
                                        </td>
                                        <td>{{ $p->buku->judul ?? '-' }}</td>
                                        <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(7)->format('d/m/Y') : '-' }}</td>    
                                        <td>
                                            @php
                                                $jatuhTempo = $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(7) : null;
                                                $now = \Carbon\Carbon::now();
                                                $terlambat = $jatuhTempo ? $now->diffInDays($jatuhTempo, false) : 0;
                                            @endphp
                                            @if($terlambat < 0)
                                                <span class="badge bg-danger">Terlambat {{ abs($terlambat) }} hari</span>
                                            @else
                                                <span class="badge bg-success">Tepat Waktu</span>
                                            @endif
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-primary btn-sm" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#pengembalianModal{{ $p->id }}">
                                                <i class="fas fa-undo"></i> Kembalikan
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data peminjaman aktif</td>
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
                                    <th>Peminjam</th>
                                    <th>Buku</th>
                                    <th>Tanggal Pinjam</th>
                                    <th>Tanggal Kembali</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pengembalians as $p)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            @if($p->peminjaman && $p->peminjaman->siswa_id)
                                                {{ $p->peminjaman->siswa->nama ?? '-' }} ({{ $p->peminjaman->siswa->nis ?? '-' }})
                                                <br>
                                                <small class="text-muted">{{ $p->peminjaman->siswa->kelas->nama_kelas ?? '-' }}</small>
                                            @else
                                                {{ $p->peminjaman->guru->nama ?? '-' }} ({{ $p->peminjaman->guru->nip ?? '-' }})
                                            @endif
                                        </td>
                                        <td>{{ $p->peminjaman->buku->judul ?? '-' }}</td>
                                        <td>{{ $p->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($p->peminjaman->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                                        <td>{{ $p->tanggal_kembali ? \Carbon\Carbon::parse($p->tanggal_kembali)->format('d/m/Y') : '-' }}</td>
                                        <td>
                                            <div class="btn-group">
                                                @if(auth()->user()->hasRole('admin'))
                                                    <a href="{{ route('admin.pengembalian.edit', $p->id) }}" 
                                                       class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('admin.pengembalian.destroy', $p->id) }}" 
                                                          method="POST" 
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data pengembalian</td>
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
                    <h5 class="modal-title" id="pengembalianModalLabel{{ $p->id }}">Konfirmasi Pengembalian</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ auth()->user()->hasRole('admin') ? route('admin.pengembalian.store') : route('petugas.pengembalian.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <h6>Detail Peminjaman:</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th>Peminjam:</th>
                                    <td>
                                        @if($p->siswa_id)
                                            {{ $p->siswa->nama ?? '-' }} ({{ $p->siswa->nis ?? '-' }})
                                            <br>
                                            <small class="text-muted">{{ $p->siswa->kelas->nama_kelas ?? '-' }}</small>
                                        @else
                                            {{ $p->guru->nama ?? '-' }} ({{ $p->guru->nip ?? '-' }})
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Buku:</th>
                                    <td>{{ $p->buku->judul ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Pinjam:</th>
                                    <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Jatuh Tempo:</th>
                                    <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(7)->format('d/m/Y') : '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @php
                                            $jatuhTempo = $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->addDays(7) : null;
                                            $now = \Carbon\Carbon::now();
                                            $terlambat = $jatuhTempo ? $now->diffInDays($jatuhTempo, false) : 0;
                                        @endphp
                                        @if($terlambat < 0)
                                            <span class="badge bg-danger">Terlambat {{ abs($terlambat) }} hari</span>
                                        @else
                                            <span class="badge bg-success">Tepat Waktu</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                            <input type="hidden" name="peminjaman_id" value="{{ $p->id }}">
                            <input type="hidden" name="tanggal_kembali" value="{{ now()->format('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Konfirmasi Kembalikan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach
@endsection