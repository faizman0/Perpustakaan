@extends('layouts.main')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh; margin-top:-50px;">
    <div class="row w-100 justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg animate__animated animate__fadeIn" style="border-radius: 1rem;">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h4 class="mb-0 font-weight-bold">Login</h4>
                        <p class="text-muted mb-0" style="font-size: 1rem;">Silakan login untuk melanjutkan</p>
                    </div>
                    <form action="{{ route('login') }}" method="post">
                        @csrf
                        <div class="form-floating mb-3">
                            <label for="username">Username</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" id="username" placeholder="Username" value="{{ old('username') }}" required autofocus>
                            @error('username')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <label for="password">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password" required>
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary w-100">Masuk</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection