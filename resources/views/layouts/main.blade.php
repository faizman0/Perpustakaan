<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title') | Perpustakaan SDN Banguntapan</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Animate.css -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- Custom styles -->
  <style>
    .radio {
    width: 50px;
    height: 20px;

    }

    .btn {
      transition: all 0.2s ease;
    }
    .btn:hover {
      transform: scale(1.05);
    }
    .nav-link:not(.dropdown-toggle) {
      position: relative;
    }
    .nav-link:not(.dropdown-toggle)::after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 0;
      background-color: #007bff;
      transition: width 0.4s ease;
    }
    .nav-link:not(.dropdown-toggle):hover::after {
      width: 100%;
    }
    .table {
      
    }
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
  </style>
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Bootstrap 4 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Bootstrap 4 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      @auth
      <!-- Notifications Dropdown Menu -->
      @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('petugas'))
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-bell fa-lg"></i>
          @if(isset($peminjamanTerlambat) && $peminjamanTerlambat->count() > 0)
          <span class="badge badge-warning navbar-badge">{{ $peminjamanTerlambat->count() }}</span>
          @endif
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          @if(isset($peminjamanTerlambat) && $peminjamanTerlambat->count() > 0)
          <span class="dropdown-item dropdown-header">{{ $peminjamanTerlambat->count() }} Peminjaman Terlambat</span>
          <div class="dropdown-divider"></div>
          @foreach($peminjamanTerlambat as $peminjaman)
          <a href="{{ (auth()->user()->hasRole('admin') ? route('admin.pengembalian.index') : route('petugas.pengembalian.index')) . '#aktif' }}" class="dropdown-item">
            <div class="d-flex">
              <div class="flex-grow-1">
                <strong>{{ $peminjaman->buku->judul }}</strong><br>
                <small>Peminjam: {{ $peminjaman->anggota->nama }}</small>
              </div>
              <div class="ml-2 text-right">
                @php
                    $terlambat = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->addDays(14));
                @endphp
                <span class="badge badge-danger">{{ $terlambat }} hari</span>
              </div>
            </div>
          </a>
          <div class="dropdown-divider"></div>
          @endforeach
          <a href="{{ (auth()->user()->hasRole('admin') ? route('admin.pengembalian.index') : route('petugas.pengembalian.index')) . '#aktif' }}" class="dropdown-item dropdown-footer">Lihat Semua Peminjaman</a>
          @else
          <span class="dropdown-item dropdown-header">Tidak ada peminjaman terlambat</span>
          @endif
        </div>
      </li>
      @endif

      <!-- User Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user"></i> {{ Auth::user()->name }}
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarUserDropdown">
          <a href="{{ route('users.edit-password') }}" class="dropdown-item">
            <i class="fas fa-key me-2"></i> Ubah Password
          </a>
          <div class="dropdown-divider"></div>
          <form action="{{ route('logout') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="dropdown-item">
              <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
          </form>
        </div>
      </li>
      @else
      <li class="nav-item">
        <a href="{{ route('login') }}" class="nav-link">
          <i class="bi bi-box-arrow-in-right"></i> Login
        </a>
      </li>
      @endauth
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" style="text-decoration: none;" class="brand-link d-sm-flex align-items-center justify-content-center p-2">
      <img src="{{ asset('img/logoSD.png') }}" alt="Logo Perpustakaan" class="brand-image img-circle elevation-3" style="width: 45px; height: 45px; margin-right: 10px;">
      <span class="brand-text font-weight-bold" style="font-size: 1.2rem; letter-spacing: 0.5px;">
        Perpustakaan<br>
        <small style="font-size: 0.9rem; opacity: 0.8;">Sumber Ilmu</small>
      </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          @auth
          <li class="nav-item">
            <a href="/dashboard" class="nav-link {{ $key == 'dashboard' ? 'active' : '' }}">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>
          @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('petugas'))
          <li class="nav-item has-treeview {{ in_array($key, ['kategori', 'buku']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ in_array($key, ['kategori', 'buku']) ? 'active' : '' }}">
              <i class="nav-icon fas fa-book-open"></i>
              <p>
                Koleksi Buku
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 1rem;">
              @if(auth()->user()->hasRole('admin'))
              <li class="nav-item">
                <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ $key == 'kategori' ? 'active' : '' }}">
                  <i class="fas fa-tags nav-icon"></i>
                  <p>Kategori Buku</p>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.buku.index') : route('petugas.buku.index') }}" class="nav-link {{ $key == 'buku' ? 'active' : '' }}">
                  <i class="fas fa-book nav-icon"></i>
                  <p>Data Buku</p>
                </a>
              </li>
            </ul>
          </li>
          
          @endif

          
          <li class="nav-item has-treeview {{ in_array($key, ['anggota', 'guru', 'kelas', 'siswa']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ in_array($key, ['anggota', 'guru', 'kelas', 'siswa']) ? 'active' : '' }}">
                <i class="nav-icon fas fa-id-card"></i>
                <p>
                    Anggota
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 1rem;">
                <li class="nav-item">
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.anggota.index') : route('petugas.anggota.index') }}" class="nav-link {{ $key == 'anggota' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-id-card"></i>
                        <p>Data Anggota</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.guru.index') : route('petugas.guru.index') }}" class="nav-link {{ $key == 'guru' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user-tie"></i>
                        <p>Guru</p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{ in_array($key, ['kelas', 'siswa']) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ in_array($key, ['kelas', 'siswa']) ? 'active' : '' }}">
                        <i class="nav-icon fas fa-users"></i>
                        <p>
                            Data Siswa
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview" style="margin-left: 1rem;">
                        @if(auth()->user()->hasRole('admin'))
                        <li class="nav-item">
                            <a href="{{ route('admin.kelas.index') }}" class="nav-link {{ $key == 'kelas' ? 'active' : '' }}">
                                <i class="fas fa-school nav-icon"></i>
                                <p>Kelas</p>
                            </a>
                        </li>
                        @endif
                        <li class="nav-item">
                            <a href="{{ auth()->user()->hasRole('admin') ? route('admin.siswa.index') : route('petugas.siswa.index') }}" class="nav-link {{ $key == 'siswa' ? 'active' : '' }}">
                                <i class="fas fa-user-graduate nav-icon"></i>
                                <p>Siswa</p>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
          </li>

          <li class="nav-item has-treeview {{ in_array($key, ['kunjungan','peminjaman', 'pengembalian']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ in_array($key, ['kunjungan','peminjaman', 'pengembalian']) ? 'active' : '' }}">
              <i class="nav-icon fas fa-exchange-alt"></i>
              <p>
                Transaksi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 1rem;">
              <li class="nav-item">
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.kunjungan.index') : route('petugas.kunjungan.index') }}" class="nav-link {{ $key == 'kunjungan' ? 'active' : '' }}">
                  <i class="nav-icon fas fa-calendar-check"></i>
                  <p>Kunjungan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.peminjaman.index') : route('petugas.peminjaman.index') }}" class="nav-link {{ $key == 'peminjaman' ? 'active' : '' }}">
                  <i class="fas fa-arrow-right nav-icon"></i>
                  <p>Peminjaman</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ auth()->user()->hasRole('admin') ? route('admin.pengembalian.index') : route('petugas.pengembalian.index') }}" class="nav-link {{ $key == 'pengembalian' ? 'active' : '' }}">
                  <i class="fas fa-arrow-left nav-icon"></i>
                  <p>Pengembalian</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <i class="fas fa-sign-out-alt nav-icon"></i>
              <p>Logout</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
          @endauth
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>@yield('title')</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
              <li class="breadcrumb-item active">@yield('title')</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        @if (session('success'))
          <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            {{ session('success') }}
          </div>
        @endif
        @if (session('warning'))
          <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h5><i class="fas fa-exclamation-triangle"></i> Warning!</h5>
            {{ session('warning') }}
          </div>
        @endif
        @if (session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
            <h5><i class="fas fa-times-circle"></i> Error!</h5>
            {{ session('error') }}
          </div>
        @endif
        
        @yield('content')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
</div>

<!-- REQUIRED SCRIPTS -->
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- Bootstrap 5 CSS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<!-- DataTables -->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

<script>
  $(document).ready(function() {
    // Inisialisasi DataTables
    $('.datatable').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
      "language": {
        "search": "Cari:",
        "lengthMenu": "Tampilkan _MENU_ data per halaman",
        "zeroRecords": "Data tidak ditemukan",
        "info": "Menampilkan halaman _PAGE_ dari _PAGES_",
        "infoEmpty": "Tidak ada data yang tersedia",
        "infoFiltered": "(difilter dari _MAX_ total data)",
        "paginate": {
          "first": "Pertama",
          "last": "Terakhir", 
          "next": "Selanjutnya",
          "previous": "Sebelumnya"
        }
      }
    });
    
    // Initialize all DataTables with specific IDs
    ['#tabelSiswa',
      '#tabelGuru',
      '#tabelKelas',
      '#tabelKunjungan',
      '#tabelKategori',
       '#tabelBuku',
        '#tabelPeminjaman',
         '#tabelPeminjamanBuku',
         '#tabelPengembalian',
         '#tabelPeminjamanGuru',
          '#tabelPeminjamanSiswa',
          '#tabelAnggota'].forEach(function(tableId) {
      if ($(tableId).length) {
        $(tableId).DataTable();
      }
    });

    // Password toggle functionality
    $('.toggle-password').on('click', function() {
      const targetId = $(this).data('target');
      const input = $('#' + targetId);
      const icon = $(this).find('i');
      
      if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
      } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
      }
    });
  });
</script>
</body>
</html>