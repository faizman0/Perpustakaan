<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-spy="scroll" data-bs-target="#navbarNav">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Perpustakaan SDN Banguntapan</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- AOS Animation -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <!-- Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        .hero-section {
            background-color: #ffffff;
            padding: 120px 0;
            color: #0A2351; /* Biru Tua */
        }

        .hero-section h1 {
            font-size: 3.5rem;
            font-weight: 700;
        }

        .hero-section p {
            font-size: 1.5rem;
        }

        .feature-icon {
            font-size: 3rem;
            color: #0d6efd; 
            transition: transform 0.3s ease;
        }

        .card:hover .feature-icon {
            transform: scale(1.1);
        }

        .section {
            padding: 100px 0;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .btn-primary {
            background-color: #0d6efd;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0b5ed7;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="antialiased">

<!-- Navbar -->
<nav id="navbarNav" class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">
            <img src="{{ asset('img/logoSD.png') }}" alt="Logo"> Perpustakaan Sumber Ilmu
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="#home">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="#features">Fitur</a></li>
                <li class="nav-item"><a class="nav-link" href="#location">Lokasi</a></li>
                @auth
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-2 px-3" href="{{ url('/dashboard') }}">Dashboard</a></li>
                @else
                    <li class="nav-item"><a class="nav-link btn btn-primary text-white ms-2 px-3" href="{{ route('login') }}">Login</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<header class="hero-section" id="home">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6" data-aos="fade-right">
                <img src="{{ asset('img/ImageLandingPage.png') }}" alt="Perpustakaan" class="img-fluid rounded-3 shadow-lg mb-4 mb-lg-0">
            </div>
            <div class="col-lg-6 text-center text-lg-start" data-aos="fade-left">
                <h1>Selamat Datang di Perpustakaan Sumber Ilmu</h1>
                <p class="lead mt-3">Sistem Informasi Perpustakaan SDN Banguntapan.</p>
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg mt-4">Masuk ke Sistem</a>
            </div>
        </div>
    </div>
</header>

<!-- Features Section -->
<section id="features" class="section bg-light">
    <div class="container text-center">
        <h2 class="mb-5" data-aos="fade-up">Fitur Unggulan</h2>
        <div class="row g-4">
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="card p-4 shadow-sm h-100 rounded-3 border-0">
                    <i class="fas fa-book-open feature-icon mb-3"></i>
                    <h5>Koleksi Buku Lengkap</h5>
                    <p class="text-muted">Manajemen data buku menjadi lebih mudah dan terorganisir dengan baik.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="card p-4 shadow-sm h-100 rounded-3 border-0">
                    <i class="fas fa-users feature-icon mb-3"></i>
                    <h5>Manajemen Anggota</h5>
                    <p class="text-muted">Kelola data anggota perpustakaan, baik siswa maupun guru, secara efisien.</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                <div class="card p-4 shadow-sm h-100 rounded-3 border-0">
                    <i class="fas fa-exchange-alt feature-icon mb-3"></i>
                    <h5>Sirkulasi Peminjaman</h5>
                    <p class="text-muted">Catat dan lacak semua transaksi peminjaman dan pengembalian buku.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Location Section -->
<section id="location" class="section">
    <div class="container text-center">
        <h2 class="mb-5" data-aos="fade-up">Lokasi Kami</h2>
        <div class="row justify-content-center" data-aos="fade-up" data-aos-delay="100">
            <div class="col-lg-10">
                <div class="ratio ratio-16x9">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3952.8806195698744!2d110.4163304737259!3d-7.802461377451879!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a5751ea664f0f%3A0xbd23cd2ff4d86377!2zU0QgTmVnZXJpIEJhbmd1bnRhcGFuICjqprHqp4Dqpp3qp4DqpqTqprzqppLqprzqpqvqprbqpqfqppTqprjqpqTqp4DqpqDqpqXqpqTqp4Ap!5e0!3m2!1sid!2sid!4v1750596016715!5m2!1sid!2sid" width="600" height="450" style="border:0; border-radius: 15px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-4 bg-dark text-white text-center">
    <div class="container">
        <p class="mb-0">&copy; {{ date('Y') }} Perpustakaan SDN Banguntapan. Hak Cipta Dilindungi.</p>
    </div>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script> 

<!-- AOS Animation JS -->
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script> 
<script>
    AOS.init();
</script>
</body>
</html>