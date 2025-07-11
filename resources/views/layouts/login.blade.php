<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Perpustakaan SDN Banguntapan</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Animate.css -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        body {
            min-height: 100vh;
           
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Poppins', sans-serif;
        }
        .login-container {
            min-height: 100vh;
            min-width: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 400px;
            width: 100%;
            animation: fadeInDown 1s;
        }
        .login-logo {
            display: flex;
            justify-content: center;
            margin-bottom: 1.5rem;
        }
        .login-logo img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            box-shadow: 0 4px 16px rgba(0,0,0,0.08);
        }
        .login-title {
            font-weight: 700;
            color: #0d6efd;
            text-align: center;
            margin-bottom: 0.5rem;
        }
        .login-subtitle {
            color: #6c757d;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .btn-primary {
            background:  #0d6efd ;
            border: none;
            border-radius: 15px;    
        }
        .btn-primary:hover {
            background: #0b5ed7;
        }
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        @yield('content')
    </div>
    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 