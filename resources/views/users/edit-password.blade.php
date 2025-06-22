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
                            <label for="current_password">Password Saat Ini</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" required>
                            @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">Password Baru</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <button type="submit" class="btn btn-primary">Ubah Password</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
@endsection 