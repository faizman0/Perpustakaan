@extends('layouts.main')

@section('title', 'Tambah Peminjaman')

@section('content')
<div class="container-fluid">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Terjadi Kesalahan!</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">Error!</h5>
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <form action="{{ auth()->user()->hasRole('admin') ? route('admin.peminjaman.store') : route('petugas.peminjaman.store') }}" method="POST" id="peminjamanForm">
        @csrf

        <!-- Pilihan Anggota -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pilih Anggota <span class="text-warning">*</span></h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tabelAnggota">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">Pilih</th>
                                <th width="15%">Kode Anggota</th>
                                <th width="25%">Nama</th>
                                <th width="15%">Tipe</th>
                                <th width="20%">NIS/NIP</th>
                                <th width="10%">Jenis Kelamin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($anggotas as $anggota)
                                <tr onclick="document.getElementById('anggota_{{ $anggota->id }}').checked = true;" style="cursor: pointer;">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input anggota-radio" type="radio" 
                                                   name="anggota_id" value="{{ $anggota->id }}" 
                                                   id="anggota_{{ $anggota->id }}"
                                                   style="width: 35px; height: 35px; position: relative;">
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-primary">{{ $anggota->kode_anggota }}</span>
                                    </td>
                                    <td>{{ $anggota->nama }}</td>
                                    <td>
                                        @if($anggota->tipe == 'Siswa')
                                            <span class="badge badge-success">Siswa</span>
                                        @else
                                            <span class="badge badge-info">Guru</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($anggota->siswa)
                                            {{ $anggota->siswa->nis }}
                                        @elseif($anggota->guru)
                                            {{ $anggota->guru->nip }}
                                        @endif
                                    </td>
                                    <td>
                                        @if($anggota->siswa)
                                            {{ $anggota->siswa->jenis_kelamin }}
                                        @elseif($anggota->guru)
                                            {{ $anggota->guru->jenis_kelamin }}
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data anggota</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Pilihan Buku -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pilih Buku (Maksimal 3) <span class="text-warning">*</span></h5>
            </div>
            <div class="card-body" style="height: 300px; overflow-y: auto;">
                <table class="table table-bordered align-middle" id="tabelBuku">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">Pilih</th>
                            <th>Judul</th>
                            <th>Pengarang</th>
                            <th>Klasifikasi</th>
                            <th>Stok</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($bukus as $buku)
                            <tr class="{{ $buku->jumlah < 1 ? 'table-secondary' : '' }}" 
                                @if($buku->jumlah >= 1)
                                    onclick="document.getElementById('buku_{{ $buku->id }}').click()"
                                    style="cursor: pointer;"
                                @endif
                                >
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input buku-checkbox" type="checkbox" 
                                               name="buku_ids[]" value="{{ $buku->id }}" 
                                               id="buku_{{ $buku->id }}"
                                               {{ $buku->jumlah < 1 ? 'disabled' : '' }}
                                               style="width: 35px; height: 35px; position: relative;"
                                               onclick="event.stopPropagation()">
                                    </div>
                                </td>
                                <td><label for="buku_{{ $buku->id }}" style="cursor: pointer;">{{ $buku->judul }}</label></td>
                                <td>{{ $buku->pengarang }}</td>
                                <td>{{ $buku->no_klasifikasi }}</td>
                                <td class="{{ $buku->jumlah < 1 ? 'text-danger' : 'text-success' }}">
                                    {{ $buku->jumlah }}
                                    @if($buku->jumlah < 1)
                                        <span class="badge bg-danger ms-2">Stok Habis</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Tidak ada data buku</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Informasi Peminjaman -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Peminjaman</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_pinjam" class="form-label">Tanggal Peminjaman <span class="text-warning">*</span></label>
                            <input type="datetime-local" class="form-control" id="tanggal_pinjam_display" value="{{ old('tanggal_pinjam', now('Asia/Jakarta')->format('Y-m-d\\TH:i')) }}" disabled>
                            <input type="hidden" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', now('Asia/Jakarta')->format('Y-m-d\\TH:i')) }}">
                        </div>
                    </div>
                    
                </div>
                
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.peminjaman.index') : route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Peminjaman
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inisialisasi DataTable untuk tabel anggota
    $('#tabelAnggota').DataTable({
        "responsive": true,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Inisialisasi DataTable untuk tabel buku
    $('#tabelBuku').DataTable({
        "responsive": true,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data per halaman",
            "zeroRecords": "Data tidak ditemukan",
            "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
            "infoEmpty": "Tidak ada data yang tersedia",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });

    // Validasi form sebelum submit
    $('#peminjamanForm').on('submit', function(e) {
        const selectedAnggota = $('input[name="anggota_id"]:checked').val();
        const selectedBooks = $('input[name="buku_ids[]"]:checked').length;
        
        if (!selectedAnggota) {
            e.preventDefault();
            alert('Silakan pilih anggota terlebih dahulu!');
            return false;
        }

        if (selectedBooks === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal 1 buku!');
            return false;
        }

        if (selectedBooks > 3) {
            e.preventDefault();
            alert('Maksimal hanya bisa meminjam 3 buku!');
            return false;
        }

        const tanggalPeminjaman = $('#tanggal_pinjam').val();
        const tanggalPengembalian = $('#tanggal_kembali').val();
        
        if (tanggalPengembalian <= tanggalPeminjaman) {
            e.preventDefault();
            alert('Tanggal pengembalian harus lebih besar dari tanggal peminjaman!');
            return false;
        }
    });
});
</script>
@endpush