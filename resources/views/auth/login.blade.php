@extends('layouts.auth')

@section('title', 'Login')

@section('content')

<form action="{{ route('login') }}" method="post">
    @csrf

    <div class="form-floating mb-3">
        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Username" value="{{ old('username') }}" required autofocus>
        <label for="username">Username</label>
        @error('username')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="form-floating mb-3">
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" required>
        <label for="password">Password</label>
        @error('password')
            <div class="invalid-feedback">
                {{ $message }}
            </div>
        @enderror
    </div>

    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">Masuk</button>
    </div>

</form>
@endsection