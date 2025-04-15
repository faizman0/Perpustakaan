<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    {{-- IMPORT BOOTSTRAP ICONS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- IMPORT DATATABLES --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.5/css/dataTables.dataTables.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <title>@yield('title')</title>
</head>
<body>
    <div class="container-fluid">
        {{-- HEADER --}}
        <div class="row">
            <div class="col-lg-12 py-2 bg-primary text-white d-flex justify-content-between align-items-center">
                <h3 class="mb-1 py-1 font-weight-bold">Perpustakaan SDN Banguntapan</h3>
                <div class="col-mt-10">
                    <form action="/search" method="get">
                        @csrf
                        <div class="input-group mt-1">
                            <input type="text" name="query" class="form-control" placeholder="Search by book title or member name" value="{{ request()->input('query') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-info">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="dropdown float-right">
                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="bi bi-person"></i> {{ Auth::user()->name }}
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="/edituser">Change password</a>
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- SIDEBAR DAN KONTEN UTAMA --}}
        <div class="row">
            {{-- SIDEBAR --}}
            <div class="col-lg-2 vh-100 bg-dark text-white">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                            <a class="nav-link {{ $key == 'dashboard' ? 'active bg-primary' : 'text-white' }}" href="/dashboard" id="v-pills-dashboard-tab">Dashboard</a>
                            <a class="nav-link {{ $key == 'member' ? 'active bg-primary' : 'text-white' }}" href="/members" id="v-pills-member-tab">Anggota</a>
                            <a class="nav-link {{ $key == 'category' ? 'active bg-primary' : 'text-white' }}" href="/categories" id="v-pills-book-tab">Kategori Buku</a>
                            <a class="nav-link {{ $key == 'book' ? 'active bg-primary' : 'text-white' }}" href="/books" id="v-pills-book-tab">Buku</a>
                            <a class="nav-link {{ $key == 'borrowing' ? 'active bg-primary' : 'text-white' }}" href="/borrowings" id="v-pills-borrowing-tab">Peminjaman</a>
                            <a class="nav-link {{ $key == 'report' ? 'active bg-primary' : 'text-white' }}" href="/reports/monthly-borrowings" id="v-pills-report-tab">Laporan</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONTEN UTAMA --}}
            <div class="col-lg-10 vh-100 bg-light">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    {{-- IMPORT DATATABLES --}}
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.5/js/dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#membersTable').DataTable();
            $('#booksTable').DataTable();
            $('#borrowingsTable').DataTable();
            $('#bookReturnsTable').DataTable();
            $('#monthlyBorrowingsTable').DataTable();
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>