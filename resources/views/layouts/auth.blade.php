<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') | Perpustakaan SDN Banguntapan</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 2rem 0;
        }

        .auth-container {
            display: flex;
            width: 100%;
            max-width: 1100px;
            background: #fff;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .auth-image-section {
            background: linear-gradient(rgba(13, 110, 253, 0.5), rgba(13, 110, 253, 0.5)), url('{{ asset('img/ImageLandingPage.png') }}') no-repeat center center;
            background-size: cover;
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .auth-image-section .content {
            padding: 40px;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 15px;
        }

        .auth-form-section {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }

        .auth-form-container {
            max-width: 450px;
            width: 100%;
        }

        @media (max-width: 991.98px) {
            .auth-image-section {
                display: none;
            }
            .auth-form-section {
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-image-section">
            <div class="content">
                <img src="{{ asset('img/logoSD.png') }}" alt="Logo" style="width: 100px; margin-bottom: 20px;">
                <h1>Perpustakaan<br>Sumber Ilmu</h1>
                <p>SDN Banguntapan</p>
            </div>
        </div>
        <div class="auth-form-section">
            <div class="auth-form-container">
                <div class="text-center mb-4">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('img/logoSD.png') }}" alt="Logo" style="width: 80px; margin-bottom: 10px;">
                    </a>
                    <h3>Login ke Akun Anda</h3>
                    <p class="text-muted">Selamat datang kembali!</p>
                </div>
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 
</body>

</html> 