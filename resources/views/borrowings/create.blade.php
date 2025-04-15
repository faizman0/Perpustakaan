@extends('layouts.main')

@section('title', 'Tambah Peminjaman')

@section('content')
    <div class="container-fluid">
        <h1 class="mb-4">Tambah Peminjaman</h1>
        
        <form action="{{ route('borrowings.store') }}" method="POST">
            @csrf
            
            <!-- Select Anggota -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Data Anggota</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="member_id" class="form-label">Pilih Anggota</label>
                        <select class="form-control" id="member_id" name="member_id" required>
                            <option value="">-- Pilih Anggota --</option>
                            @foreach ($members as $member)
                                <option value="{{ $member->id }}">{{ $member->name }} ({{ $member->member_id }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Pilihan Buku dengan Checkbox -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Pilihan Buku</h5>
                </div>
                <div class="card-body">
                    @if($books->isEmpty())
                        <div class="alert alert-warning">Tidak ada buku tersedia untuk dipinjam</div>
                    @else
                        <div class="row">
                            @foreach ($books as $book)
                                <div class="col-md-4 mb-3">
                                    <div class="card h-100 {{ $book->stock < 1 ? 'border-danger' : 'border-success' }}">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input book-checkbox" 
                                                       type="checkbox" 
                                                       name="book_ids[]" 
                                                       id="book_{{ $book->id }}" 
                                                       value="{{ $book->id }}"
                                                       {{ $book->stock < 1 ? 'disabled' : '' }}>
                                                <label class="form-check-label" for="book_{{ $book->id }}">
                                                    <strong>{{ $book->title }}</strong>
                                                </label>
                                            </div>
                                            <ul class="list-unstyled mt-2">
                                                <li><small>Pengarang: {{ $book->author }}</small></li>
                                                <li><small>Penerbit: {{ $book->publisher }}</small></li>
                                                <li><small>Tahun: {{ $book->year }}</small></li>
                                                <li>
                                                    <small>Stok: 
                                                        <span class="{{ $book->stock < 1 ? 'text-danger' : 'text-success' }}">
                                                            {{ $book->stock }}
                                                        </span>
                                                    </small>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Tanggal Peminjaman -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Informasi Peminjaman</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="borrow_date" class="form-label">Tanggal Peminjaman</label>
                        <input type="date" class="form-control" id="borrow_date" name="borrow_date" 
                               value="{{ date('Y-m-d') }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="return_date" class="form-label">Tanggal Jatuh Tempo (Opsional)</label>
                        <input type="date" class="form-control" id="return_date" name="return_date">
                    </div>
                </div>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('borrowings.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary" id="submit-btn">
                    <i class="fas fa-save"></i> Simpan Peminjaman
                </button>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            transition: box-shadow 0.3s ease;
        }
        .form-check-input:checked ~ .card {
            border: 2px solid #0d6efd !important;
        }
        .form-check-input:disabled ~ label {
            color: #6c757d;
            text-decoration: line-through;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Set tanggal peminjaman default ke hari ini
            document.getElementById('borrow_date').valueAsDate = new Date();
            
            // Validasi sebelum submit
            $('form').submit(function(e) {
                const checkedBoxes = $('.book-checkbox:checked');
                if (checkedBoxes.length === 0) {
                    e.preventDefault();
                    alert('Pilih minimal 1 buku untuk dipinjam');
                    return false;
                }
                
                // Tampilkan loading
                $('#submit-btn').prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');
            });
            
            // Toggle card style ketika checkbox di-check
            $('.book-checkbox').change(function() {
                $(this).closest('.card').toggleClass('border-primary', this.checked);
            });
        });
    </script>
@endpush