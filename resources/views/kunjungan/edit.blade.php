@extends('layouts.main')

@section('title', 'Edit Kunjungan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Edit Kunjungan</h5>
        </div>
        <div class="card-body">
            <form action="{{ \App\Helpers\AppHelper::getUpdateRoute('kunjungan', $kunjungan->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="tipe_pengunjung" class="form-label">Tipe Pengunjung</label>
                    <select class="form-select @error('tipe_pengunjung') is-invalid @enderror" id="tipe_pengunjung" name="tipe_pengunjung" required>
                        <option value="">Pilih Tipe Pengunjung</option>
                        <option value="siswa" {{ old('tipe_pengunjung', $kunjungan->tipe_pengunjung) == 'siswa' ? 'selected' : '' }}>Siswa</option>
                        <option value="guru" {{ old('tipe_pengunjung', $kunjungan->tipe_pengunjung) == 'guru' ? 'selected' : '' }}>Guru</option>
                    </select>
                    @error('tipe_pengunjung')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="siswa_select" style="display: {{ old('tipe_pengunjung', $kunjungan->tipe_pengunjung) == 'siswa' ? 'block' : 'none' }}">
                    <label for="siswa_id" class="form-label">Siswa</label>
                    <select class="form-select @error('siswa_id') is-invalid @enderror" id="siswa_id" name="siswa_id">
                        <option value="">Pilih Siswa</option>
                        @foreach($siswas as $siswa)
                            <option value="{{ $siswa->id }}" {{ old('siswa_id', $kunjungan->siswa_id) == $siswa->id ? 'selected' : '' }}>
                                {{ $siswa->nama }} - {{ $siswa->nis }} - {{ $siswa->kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    @error('siswa_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3" id="guru_select" style="display: {{ old('tipe_pengunjung', $kunjungan->tipe_pengunjung) == 'guru' ? 'block' : 'none' }}">
                    <label for="guru_id" class="form-label">Guru</label>
                    <select class="form-select @error('guru_id') is-invalid @enderror" id="guru_id" name="guru_id">
                        <option value="">Pilih Guru</option>
                        @foreach($gurus as $guru)
                            <option value="{{ $guru->id }}" {{ old('guru_id', $kunjungan->guru_id) == $guru->id ? 'selected' : '' }}>
                                {{ $guru->nama }} - {{ $guru->nip }}
                            </option>
                        @endforeach
                    </select>
                    @error('guru_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan</label>
                    <input type="date" class="form-control @error('tanggal_kunjungan') is-invalid @enderror" id="tanggal_kunjungan" name="tanggal_kunjungan" value="{{ old('tanggal_kunjungan', $kunjungan->tanggal_kunjungan) }}" required>
                    @error('tanggal_kunjungan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan" rows="3">{{ old('keterangan', $kunjungan->keterangan) }}</textarea>
                    @error('keterangan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between">
                    {!! \App\Helpers\AppHelper::getBackButton('kunjungan') !!}
                    {!! \App\Helpers\AppHelper::getSubmitButton('Update Kunjungan') !!}
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#tipe_pengunjung').change(function() {
        var tipe = $(this).val();
        if (tipe === 'siswa') {
            $('#siswa_select').show();
            $('#guru_select').hide();
            $('#siswa_id').prop('required', true);
            $('#guru_id').prop('required', false);
        } else if (tipe === 'guru') {
            $('#siswa_select').hide();
            $('#guru_select').show();
            $('#siswa_id').prop('required', false);
            $('#guru_id').prop('required', true);
        } else {
            $('#siswa_select').hide();
            $('#guru_select').hide();
            $('#siswa_id').prop('required', false);
            $('#guru_id').prop('required', false);
        }
    });
});
</script>
@endpush
