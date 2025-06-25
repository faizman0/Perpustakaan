@extends('layouts.main')

@section('title', 'Tambah Kunjungan')

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

    <form action="{{ auth()->user()->hasRole('admin') ? route('admin.kunjungan.store') : route('petugas.kunjungan.store') }}" method="POST" id="kunjunganForm">
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
                                <tr onclick="let cb = document.getElementById('anggota_{{ $anggota->id }}'); cb.checked = !cb.checked;" style="cursor: pointer;">
                                    <td>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="anggota_ids[]" value="{{ $anggota->id }}"
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

        <!-- Informasi Kunjungan -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Informasi Kunjungan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan <span class="text-warning">*</span></label>
                            <input type="datetime-local" class="form-control" id="tanggal_kunjungan_display" value="{{ old('tanggal_kunjungan', now('Asia/Jakarta')->format('Y-m-d\\TH:i')) }}" disabled>
                            <input type="hidden" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', now('Asia/Jakarta')->format('Y-m-d\\TH:i')) }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3" 
                                      placeholder="Masukkan keterangan kunjungan">{{ old('keterangan') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between mb-4 mt-4">
            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.kunjungan.index') : route('petugas.kunjungan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Kunjungan
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

    // Validasi form sebelum submit
    $('#kunjunganForm').on('submit', function(e) {
        const selectedAnggota = $('input[name="anggota_ids[]"]:checked').length;
        
        if (selectedAnggota === 0) {
            e.preventDefault();
            alert('Silakan pilih minimal satu anggota!');
            return false;
        }

        const tanggalKunjungan = $('#tanggal_kunjungan').val();
        const today = new Date().toISOString().split('T')[0];
        
        if (tanggalKunjungan > today) {
            e.preventDefault();
            alert('Tanggal kunjungan tidak boleh melebihi hari ini!');
            return false;
        }
    });
});
</script>
@endpush
