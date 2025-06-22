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

    <form action="{{ auth()->user()->hasRole('admin') ? route('admin.peminjaman.store') : route('petugas.peminjaman.store') }}" method="POST" id="peminjamanForm">
        @csrf

        <!-- Pilihan Tipe Peminjam -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Pilih Jenis Peminjam <span class="text-warning">*</span></h5>
            </div>
            <div class="card-body">
                <!-- Tab Navigation -->
                <ul class="nav nav-tabs mb-3" id="peminjamTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="siswa-tab" data-bs-toggle="tab" 
                                data-bs-target="#siswa" type="button" role="tab" 
                                aria-controls="siswa" aria-selected="true">
                            <i class="fas fa-user-graduate me-1"></i> Siswa
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="guru-tab" data-bs-toggle="tab" 
                                data-bs-target="#guru" type="button" role="tab" 
                                aria-controls="guru" aria-selected="false">
                            <i class="fas fa-chalkboard-teacher me-1"></i> Guru
                        </button>
                    </li>
                </ul>
                
                <!-- Tab Content -->
                <div class="tab-content" id="peminjamTabContent">
                    <input type="hidden" name="tipe_peminjam" id="tipe_peminjam" value="siswa">
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
                                                    <input class="form-check-input peminjam-radio" type="radio" 
                                                           name="siswa_id" value="{{ $siswa->id }}" 
                                                           id="siswa_{{ $siswa->id }}"
                                                           onchange="handlePeminjamChange('siswa')">
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
                                                    <input class="form-check-input peminjam-radio" type="radio" 
                                                           name="guru_id" value="{{ $guru->id }}" 
                                                           id="guru_{{ $guru->id }}"
                                                           onchange="handlePeminjamChange('guru')">
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
                function handlePeminjamChange(tipe) {
                    // Update hidden input value
                    document.getElementById('tipe_peminjam').value = tipe;
                    
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
                            <tr class="{{ $buku->jumlah < 1 ? 'table-secondary' : '' }}">
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input buku-checkbox" type="checkbox" 
                                               name="buku_ids[]" value="{{ $buku->id }}" 
                                               id="buku_{{ $buku->id }}"
                                               {{ $buku->jumlah < 1 ? 'disabled' : '' }}>
                                    </div>
                                </td>
                                <td><label for="buku_{{ $buku->id }}">{{ $buku->judul }}</label></td>
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
                            <input type="date" class="form-control" id="tanggal_pinjam" name="tanggal_pinjam" 
                                   value="{{ date('Y-m-d') }}" required>
                            <div class="form-text">Tanggal tidak boleh melebihi hari ini</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="d-flex justify-content-between">
            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.peminjaman.index') : route('petugas.peminjaman.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <button type="submit" class="btn btn-primary" id="submit-btn">
                <i class="fas fa-save"></i> Simpan Peminjaman
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi nilai default
        $('#tipe_peminjam').val('siswa');
        
        // Handle perubahan tab
        $('button[data-bs-toggle="tab"]').on('shown.bs.tab', function (e) {
            const target = $(e.target).data('bs-target');
            const tipePeminjam = target === '#siswa' ? 'siswa' : 'guru';
            $('#tipe_peminjam').val(tipePeminjam);
            
            // Reset radio button yang tidak aktif
            if (tipePeminjam === 'siswa') {
                $('input[name="guru_id"]').prop('checked', false);
            } else {
                $('input[name="siswa_id"]').prop('checked', false);
            }
        });

        // Handle pemilihan radio button
        $('input[name="siswa_id"], input[name="guru_id"]').change(function() {
            const isSiswa = $(this).attr('name') === 'siswa_id';
            $('#tipe_peminjam').val(isSiswa ? 'siswa' : 'guru');
            
            // Aktifkan tab yang sesuai
            if (isSiswa) {
                $('#siswa-tab').tab('show');
            } else {
                $('#guru-tab').tab('show');
            }
        });

        // Validasi sebelum submit
        $('#peminjamanForm').submit(function(e) {
            let errors = [];
            
            // Validasi peminjam
            const tipePeminjam = $('#tipe_peminjam').val();
            const siswaSelected = $('input[name="siswa_id"]:checked').length > 0;
            const guruSelected = $('input[name="guru_id"]:checked').length > 0;
            
            if ((tipePeminjam === 'siswa' && !siswaSelected) || 
                (tipePeminjam === 'guru' && !guruSelected)) {
                errors.push('• Pilih peminjam yang sesuai');
            }
            
            // Validasi buku
            const bukuSelected = $('.buku-checkbox:checked').length;
            if (bukuSelected === 0) {
                errors.push('• Pilih minimal 1 buku');
            } else if (bukuSelected > 3) {
                errors.push('• Maksimal 3 buku yang dapat dipinjam');
            }
            
            // Validasi tanggal
            const today = new Date().toISOString().split('T')[0];
            const tanggalPinjam = $('#tanggal_pinjam').val();
            
            if (!tanggalPinjam) {
                errors.push('• Tanggal peminjaman harus diisi');
            } else if (tanggalPinjam > today) {
                errors.push('• Tanggal peminjaman tidak boleh melebihi hari ini');
            }
            
            // Tampilkan error jika ada
            if (errors.length > 0) {
                e.preventDefault();
                const errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                                   '<h5 class="alert-heading">Perbaiki kesalahan berikut:</h5>' +
                                   '<ul class="mb-0">' + errors.map(e => '<li>' + e + '</li>').join('') + '</ul>' +
                                   '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                                   '</div>';
                
                // Hapus alert sebelumnya dan tambahkan yang baru
                $('.alert-danger').remove();
                $('#peminjamanForm').prepend(errorHtml);
                
                // Scroll ke atas
                window.scrollTo(0, 0);
            }
        });
        
        // Nonaktifkan checkbox buku yang stoknya habis
        $('.buku-checkbox').each(function() {
            if ($(this).is(':disabled')) {
                $(this).closest('tr').addClass('table-secondary');
            }
        });
    });
</script>
@endsection