@extends('layouts.main')

@section('title', 'Tambah Pengguna')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Pengguna Baru</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                            <label>Peran</label>
                            <div class="select2-purple">
                                <select class="select2" multiple="multiple" data-placeholder="Pilih peran" data-dropdown-css-class="select2-purple" style="width: 100%;" name="roles[]" required>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', [])) ? 'selected' : '' }}>
                                        {{ $role->nama }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('roles')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ttskch/select2-bootstrap4-theme@x.x.x/dist/select2-bootstrap4.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(function () {
        //Initialize Select2 Elements
        $('.select2').select2({
            theme: 'bootstrap4'
        });
    });
</script>
@endpush 