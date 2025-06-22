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

    <form action="{{ auth()->user()->hasRole('admin') ? route('admin.kunjungan.store') : route('petugas.kunjungan.store') }}" method="POST" id="kunjunganForm">
        @csrf

        <!-- Pilihan Tipe Pengunjung -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pilih Jenis Pengunjung <span class="text-warning">*</span></h5>
            </div>
            <div class="card-body">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-3" id="pengunjungTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="siswa-tab" data-bs-toggle="tab" 
                                data-bs-target="#siswa" type="button" role="tab" 
                                aria-controls="siswa" aria-selected="true"
                                onclick="handleTabChange('siswa')">
                            <i class="fas fa-user-graduate me-1"></i> Siswa
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="guru-tab" data-bs-toggle="tab" 
                                data-bs-target="#guru" type="button" role="tab" 
                                aria-controls="guru" aria-selected="false"
                                onclick="handleTabChange('guru')">
                            <i class="fas fa-chalkboard-teacher me-1"></i> Guru
                        </button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content" id="pengunjungTabContent">
                    <input type="hidden" name="tipe_pengunjung" id="tipe_pengunjung" value="siswa">
                    <div class="tab-pane fade show active" id="siswa" role="tabpanel" aria-labelledby="siswa-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tabelSiswa">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">Pilih</th>
                                        <th>Nama</th>
                                        <th>NIS</th>
                                        <th>Kelas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswas as $siswa)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input pengunjung-radio" type="radio" 
                                                           name="siswa_id" value="{{ $siswa->id }}" 
                                                           id="siswa_{{ $siswa->id }}"
                                                           onchange="handlePengunjungChange('siswa')">
                                                </div>
                                            </td>
                                            <td><label for="siswa_{{ $siswa->id }}">{{ $siswa->nama }}</label></td>
                                            <td>{{ $siswa->nis }}</td>
                                            <td>{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data siswa</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="guru" role="tabpanel" aria-labelledby="guru-tab">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tabelGuru">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">Pilih</th>
                                        <th>Nama</th>
                                        <th>NIP</th>
                                        <th>Jenis Kelamin</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($gurus as $guru)
                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input pengunjung-radio" type="radio" 
                                                           name="guru_id" value="{{ $guru->id }}" 
                                                           id="guru_{{ $guru->id }}"
                                                           onchange="handlePengunjungChange('guru')">
                                                </div>
                                            </td>
                                            <td><label for="guru_{{ $guru->id }}">{{ $guru->nama }}</label></td>
                                            <td>{{ $guru->nip }}</td>
                                            <td>{{ $guru->jenis_kelamin }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Tidak ada data guru</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <script>
                function handleTabChange(tipe) {
                    document.getElementById('tipe_pengunjung').value = tipe;
                    
                    // Reset radio buttons when switching tabs
                    if (tipe === 'siswa') {
                        document.querySelectorAll('input[name="guru_id"]').forEach(radio => {
                            radio.checked = false;
                        });
                    } else {
                        document.querySelectorAll('input[name="siswa_id"]').forEach(radio => {
                            radio.checked = false;
                        });
                    }
                }

                function handlePengunjungChange(tipe) {
                    document.getElementById('tipe_pengunjung').value = tipe;
                    
                    // Reset other type's radio buttons
                    if (tipe === 'siswa') {
                        document.querySelectorAll('input[name="guru_id"]').forEach(radio => {
                            radio.checked = false;
                        });
                    } else {
                        document.querySelectorAll('input[name="siswa_id"]').forEach(radio => {
                            radio.checked = false;
                        });
                    }
                }
                </script>
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
                            <input type="date" class="form-control" id="tanggal_kunjungan" name="tanggal_kunjungan" 
                                   value="{{ date('Y-m-d') }}" required>
                            <div class="form-text">Tanggal tidak boleh melebihi hari ini</div>
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
        <div class="d-flex justify-content-between">
            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.kunjungan.index') : route('petugas.kunjungan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary" id="submit-btn">
                <i class="fas fa-save"></i> Simpan Kunjungan
            </button>
        </div>
    </form></div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize DataTables for both tables
    $('#tabelSiswa, #tabelGuru').DataTable({
        "responsive": true,
        "autoWidth": false,
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Indonesian.json"
        }
    });

    // Handle radio button changes
    $('input[name="siswa_id"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('#tipe_pengunjung').val('siswa');
            $('input[name="guru_id"]').prop('checked', false);
        }
    });

    $('input[name="guru_id"]').on('change', function() {
        if ($(this).is(':checked')) {
            $('#tipe_pengunjung').val('guru');
            $('input[name="siswa_id"]').prop('checked', false);
        }
    });

    // Handle tab switching
    $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
        const targetId = $(e.target).attr('data-bs-target');
        
        // Reset radio buttons based on which tab is active
        if (targetId === '#siswa') {
            $('#tipe_pengunjung').val('siswa');
            $('input[name="guru_id"]').prop('checked', false);
        } else if (targetId === '#guru') {
            $('#tipe_pengunjung').val('guru');
            $('input[name="siswa_id"]').prop('checked', false);
        }
    });

    // Form validation
    $('#kunjunganForm').submit(function(e) {
        let errors = [];
        
        // Validasi pengunjung
        const tipePengunjung = $('#tipe_pengunjung').val();
        const siswaSelected = $('input[name="siswa_id"]:checked').length > 0;
        const guruSelected = $('input[name="guru_id"]:checked').length > 0;
        
        if ((tipePengunjung === 'siswa' && !siswaSelected) || 
            (tipePengunjung === 'guru' && !guruSelected)) {
            errors.push('• Pilih pengunjung yang sesuai');
        }
        
        // Validasi tanggal
        const today = new Date().toISOString().split('T')[0];
        const tanggalKunjungan = $('#tanggal_kunjungan').val();
        
        if (!tanggalKunjungan) {
            errors.push('• Tanggal kunjungan harus diisi');
        } else if (tanggalKunjungan > today) {
            errors.push('• Tanggal kunjungan tidak boleh melebihi hari ini');
        }
        
        // Tampilkan error jika ada
        if (errors.length > 0) {
            e.preventDefault();
            alert('Mohon perbaiki kesalahan berikut:\n' + errors.join('\n'));
        }
    });
});
</script>
@endpush
