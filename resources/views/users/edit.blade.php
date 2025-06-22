@extends('layouts.main')

@section('title', 'Edit Pengguna')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Edit Pengguna</h3>
        </div>
        <!-- /.card-header -->
        <div class="card-body">
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username', $user->username) }}" required>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password Baru (kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <div class="form-group">
                            <label>Peran</label>
                            <div class="select2-purple">
                                <select class="select2" multiple="multiple" data-placeholder="Pilih peran" data-dropdown-css-class="select2-purple" style="width: 100%;" name="roles[]" required>
                                    @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ in_array($role->id, old('roles', $user->roles->pluck('id')->toArray())) ? 'selected' : '' }}>
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
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
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