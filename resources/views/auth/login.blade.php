@extends('layouts.login')

@section('content')
<div class="login-card animate__animated animate__fadeInDown" style="max-width: 800px; width: 100%;">
    <div class="login-logo">
        <img src="{{ asset('img/logoSD.png') }}" alt="Logo Perpustakaan">
    </div>
    <div class="login-title">Perpustakaan Sumber Ilmu</div>
    <div class="login-subtitle">Silakan login untuk melanjutkan</div>
    <form action="{{ route('login') }}" method="post">
        @csrf
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Username" value="{{ old('username') }}" required autofocus>
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" required>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-primary w-100">Masuk <i ></i></button>
        </div>
    </form>
</div>
@endsection