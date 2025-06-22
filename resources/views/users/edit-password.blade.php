@extends('layouts.main')

@section('title', 'Ubah Password')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ubah Password</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('users.update-password') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                    id="password" name="password" required>
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" style="cursor: pointer;" data-target="password">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            @error('password')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i> Password harus memenuhi kriteria berikut:
                                <ul class="mt-2">
                                    <li>Minimal 8 karakter</li>
                                    <li>Mengandung huruf besar dan huruf kecil</li>
                                    <li>Mengandung angka</li>
                                </ul>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                    id="password_confirmation" name="password_confirmation" required>
                                <div class="input-group-append">
                                    <span class="input-group-text toggle-password" style="cursor: pointer;" data-target="password_confirmation">
                                        <i class="fas fa-eye"></i>
                                    </span>
                                </div>
                            </div>
                            @error('password_confirmation')
                            <div class="invalid-feedback d-block">
                                <i class="fas fa-exclamation-circle"></i> {{ $message }}
                            </div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan Password
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
@endsection

@push('scripts')
<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById(inputId + '-icon');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush 