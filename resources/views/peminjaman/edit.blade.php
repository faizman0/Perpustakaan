@extends('layouts.main')

@section('title', 'Edit Peminjaman')

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

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Peminjaman</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.peminjaman.update', $peminjaman->id) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Pilihan Tipe Peminjam -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Pilih Jenis Peminjam <span class="text-warning">*</span></h5>
                    </div>
                    <div class="card-body">
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-3" id="peminjamTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $peminjaman->siswa_id ? 'active' : '' }}" id="siswa-tab" data-bs-toggle="tab" 
                                        data-bs-target="#siswa" type="button" role="tab" 
                                        aria-controls="siswa" aria-selected="{{ $peminjaman->siswa_id ? 'true' : 'false' }}">
                                    <i class="fas fa-user-graduate me-1"></i> Siswa
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link {{ $peminjaman->guru_id ? 'active' : '' }}" id="guru-tab" data-bs-toggle="tab" 
                                        data-bs-target="#guru" type="button" role="tab" 
                                        aria-controls="guru" aria-selected="{{ $peminjaman->guru_id ? 'true' : 'false' }}">
                                    <i class="fas fa-chalkboard-teacher me-1"></i> Guru
                                </button>
                            </li>
                        </ul>
                        
                        <!-- Tab Content -->
                        <div class="tab-content" id="peminjamTabContent">
                            <input type="hidden" name="tipe_peminjam" id="tipe_peminjam" value="{{ $peminjaman->siswa_id ? 'siswa' : 'guru' }}">
                            <div class="tab-pane fade {{ $peminjaman->siswa_id ? 'show active' : '' }}" id="siswa" role="tabpanel" aria-labelledby="siswa-tab">
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
                                                                   {{ $peminjaman->siswa_id == $siswa->id ? 'checked' : '' }}
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

                            <div class="tab-pane fade {{ $peminjaman->guru_id ? 'show active' : '' }}" id="guru" role="tabpanel" aria-labelledby="guru-tab">
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
                                                                   {{ $peminjaman->guru_id == $guru->id ? 'checked' : '' }}
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
                    </div>
                </div>

                <!-- Pilihan Buku -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">Pilih Buku <span class="text-warning">*</span></h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="tabelBuku">
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
                                        <tr class="{{ $buku->jumlah < 1 && $buku->id != $peminjaman->buku_id ? 'table-secondary' : '' }}">
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" 
                                                           name="buku_id" value="{{ $buku->id }}" 
                                                           id="buku_{{ $buku->id }}"
                                                           {{ $peminjaman->buku_id == $buku->id ? 'checked' : '' }}
                                                           {{ $buku->jumlah < 1 && $buku->id != $peminjaman->buku_id ? 'disabled' : '' }}>
                                                </div>
                                            </td>
                                            <td><label for="buku_{{ $buku->id }}">{{ $buku->judul }}</label></td>
                                            <td>{{ $buku->pengarang }}</td>
                                            <td>{{ $buku->no_klasifikasi }}</td>
                                            <td class="{{ $buku->jumlah < 1 && $buku->id != $peminjaman->buku_id ? 'text-danger' : 'text-success' }}">
                                                {{ $buku->jumlah }}
                                                @if($buku->jumlah < 1 && $buku->id != $peminjaman->buku_id)
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
                                           value="{{ $peminjaman->tanggal_pinjam }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="tanggal_kembali" class="form-label">Tanggal Kembali</label>
                                    <input type="date" class="form-control" id="tanggal_kembali" name="tanggal_kembali" 
                                           value="{{ $peminjaman->tanggal_kembali }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
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
@endsection