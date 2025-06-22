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
    .btn {
      transition: all 0.2s ease;
    }
    .btn:hover {
      transform: scale(1.05);
    }
    .nav-link {
      position: relative;
    }
    .nav-link:after {
      content: '';
      position: absolute;
      width: 0;
      height: 2px;
      bottom: 0;
      left: 0;
      background-color: #007bff;
      transition: width 0.4s ease;
    }
    .nav-link:hover:after {
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
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <!-- Bootstrap 5 JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <!-- DataTables -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="hold-transition sidebar-mini">
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
    <ul class="navbar-nav ms-auto ">
      @auth
      <!-- User Dropdown Menu -->
      <li class="nav-item dropdown">
  <a class="nav-link dropdown-toggle" href="#" id="navbarUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    <i class="fas fa-user"></i> {{ Auth::user()->name }}
  </a>
  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarUserDropdown">
    <li>
      <a href="/edituser" class="dropdown-item">
        <i class="fas fa-key me-2"></i> Change Password
      </a>
    </li>
    <li><hr class="dropdown-divider"></li>
    <li>
      <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="dropdown-item">
          <i class="fas fa-sign-out-alt me-2"></i> Logout
        </button>
      </form>
    </li>
  </ul>
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
    <a href="/dashboard" class="brand-link">
      <span class="brand-text font-weight-light-bold">Perpustakaan Sumber Ilmu</span>
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

          <li class="nav-item has-treeview {{ in_array($key, ['kategori', 'buku']) ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ in_array($key, ['kategori', 'buku']) ? 'active' : '' }}">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Data Buku
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview" style="margin-left: 1rem;">
              <li class="nav-item">
                <a href="{{ route('admin.kategori.index') }}" class="nav-link {{ $key == 'kategori' ? 'active' : '' }}">
                  <i class="fas fa-tags nav-icon"></i>
                  <p>Kategori Buku</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{{ route('admin.buku.index') }}" class="nav-link {{ $key == 'buku' ? 'active' : '' }}">
                  <i class="fas fa-book nav-icon"></i>
                  <p>Buku</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="{{ route('admin.users.index') }}" class="nav-link {{ $key == 'users' ? 'active' : '' }}">
              <i class="nav-icon fas fa-users-cog"></i>
              <p>Manajemen User</p>
            </a>
          </li>
          @endif

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
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <h5><i class="icon fas fa-check"></i> Success!</h5>
            {{ session('success') }}
          </div>
        @endif
        
        <!-- @if(auth()->check())
            <div class="alert alert-info">
                <h5>Debug Information:</h5>
                <p>User: {{ auth()->user()->name }}</p>
                <p>Email: {{ auth()->user()->email }}</p>
                <p>Roles: 
                    @foreach(auth()->user()->roles as $role)
                        {{ $role->nama }} ({{ $role->slug }}),
                    @endforeach
                </p>
                <p>Has Admin Role: {{ auth()->user()->hasRole('admin') ? 'Yes' : 'No' }}</p>
                <p>Has Guru Permission: {{ auth()->user()->hasPermission('index-guru') ? 'Yes' : 'No' }}</p>
            </div>
        @endif -->
        
        @yield('content')
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <!-- <footer class="main-footer">
    <strong>Copyright &copy; 2023 <a href="#">Perpustakaan SDN Banguntapan</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0.0
    </div>
  </footer> -->
</div>
<!-- ./wrapper -->

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
          '#tabelPeminjamanSiswa'].forEach(function(tableId) {
      if ($(tableId).length) {
        $(tableId).DataTable();
      }
    });
  });
</script>
</body>
</html>