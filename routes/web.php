<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\KunjunganController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\LandingPageController;

// Route yang bisa diakses tanpa login
Route::get('/', [PageController::class, 'index'])->name('home');
Route::get('/dashboard', [PageController::class, 'index'])->middleware('auth')->name('dashboard');
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Route yang memerlukan login
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // User profile routes
    Route::get('/edituser', [UserController::class, 'editPassword'])->name('users.edit-password');
    Route::post('/edituser', [UserController::class, 'updatePassword'])->name('users.update-password');
    
    // Route khusus admin
    Route::middleware(['role:admin'])->prefix('admin')->group(function () {
        // Manajemen User
        Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('admin.users.create');
        Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        
        // Manajemen Anggota
        Route::get('/anggota', [AnggotaController::class, 'index'])->name('admin.anggota.index');
        Route::post('/anggota', [AnggotaController::class, 'store'])->name('admin.anggota.store');
        Route::get('/anggota/search', [AnggotaController::class, 'search'])->name('admin.anggota.search');
        Route::get('/anggota/{anggota}', [AnggotaController::class, 'show'])->name('admin.anggota.show');
        Route::delete('/anggota/{anggota}', [AnggotaController::class, 'destroy'])->name('admin.anggota.destroy');
        
        // Manajemen Buku
        Route::get('/buku', [BukuController::class, 'index'])->name('admin.buku.index');
        Route::get('/buku/create', [BukuController::class, 'create'])->name('admin.buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('admin.buku.store');
        Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('admin.buku.show');
        Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('admin.buku.edit');
        Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('admin.buku.update');
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('admin.buku.destroy');
        Route::post('/buku/destroy-multiple', [BukuController::class, 'destroyMultiple'])->name('admin.buku.destroy.multiple');
        Route::get('/buku/export/excel', [BukuController::class, 'exportExcel'])->name('admin.buku.export.excel');
        Route::get('/buku/export/pdf', [BukuController::class, 'exportPDF'])->name('admin.buku.export.pdf');
        Route::post('/buku/import/excel', [BukuController::class, 'importExcel'])->name('admin.buku.import.excel');
        Route::get('/buku/template/download', [BukuController::class, 'downloadTemplate'])->name('admin.buku.template.download');
        
        // Manajemen Kategori
        Route::get('/kategori', [KategoriController::class, 'index'])->name('admin.kategori.index');
        Route::get('/kategori/create', [KategoriController::class, 'create'])->name('admin.kategori.create');
        Route::post('/kategori', [KategoriController::class, 'store'])->name('admin.kategori.store');
        Route::get('/kategori/{kategori}', [KategoriController::class, 'show'])->name('admin.kategori.show');
        Route::get('/kategori/{kategori}/edit', [KategoriController::class, 'edit'])->name('admin.kategori.edit');
        Route::put('/kategori/{kategori}', [KategoriController::class, 'update'])->name('admin.kategori.update');
        Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy'])->name('admin.kategori.destroy');
        Route::get('/kategori/export/excel', [KategoriController::class, 'exportExcel'])->name('admin.kategori.export.excel');
        Route::get('/kategori/export/pdf', [KategoriController::class, 'exportPDF'])->name('admin.kategori.export.pdf');
        Route::post('/kategori/import/excel', [KategoriController::class, 'importExcel'])->name('admin.kategori.import.excel');
        Route::get('/kategori/template/download', [KategoriController::class, 'downloadTemplate'])->name('admin.kategori.template.download');
        
        // Manajemen Kelas
        Route::get('/kelas', [KelasController::class, 'index'])->name('admin.kelas.index');
        Route::get('/kelas/create', [KelasController::class, 'create'])->name('admin.kelas.create');
        Route::post('/kelas', [KelasController::class, 'store'])->name('admin.kelas.store');
        Route::get('/kelas/{kelas}', [KelasController::class, 'show'])->name('admin.kelas.show');
        Route::get('/kelas/{kelas}/edit', [KelasController::class, 'edit'])->name('admin.kelas.edit');
        Route::put('/kelas/{kelas}', [KelasController::class, 'update'])->name('admin.kelas.update');
        Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('admin.kelas.destroy');
        Route::get('/kelas/export/excel', [KelasController::class, 'exportExcel'])->name('admin.kelas.export.excel');
        Route::get('/kelas/export/pdf', [KelasController::class, 'exportPDF'])->name('admin.kelas.export.pdf');
        Route::post('/kelas/import/excel', [KelasController::class, 'importExcel'])->name('admin.kelas.import.excel');
        Route::get('/kelas/template/download', [KelasController::class, 'downloadTemplate'])->name('admin.kelas.template.download');
        
        // Manajemen Siswa
        Route::get('/siswa', [SiswaController::class, 'index'])->name('admin.siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('admin.siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('admin.siswa.store');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('admin.siswa.show');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('admin.siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('admin.siswa.update');
        Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('admin.siswa.destroy');
        Route::get('/siswa/export/excel', [SiswaController::class, 'exportExcel'])->name('admin.siswa.export.excel');
        Route::get('/siswa/export/pdf', [SiswaController::class, 'exportPDF'])->name('admin.siswa.export.pdf');
        Route::post('/siswa/import/excel', [SiswaController::class, 'importExcel'])->name('admin.siswa.import.excel');
        Route::get('/siswa/template/download', [SiswaController::class, 'downloadTemplate'])->name('admin.siswa.template.download');
        
        // Manajemen Guru
        Route::get('/guru', [GuruController::class, 'index'])->name('admin.guru.index');
        Route::get('/guru/create', [GuruController::class, 'create'])->name('admin.guru.create');
        Route::post('/guru', [GuruController::class, 'store'])->name('admin.guru.store');
        Route::get('/guru/{guru}', [GuruController::class, 'show'])->name('admin.guru.show');
        Route::get('/guru/{guru}/edit', [GuruController::class, 'edit'])->name('admin.guru.edit');
        Route::put('/guru/{guru}', [GuruController::class, 'update'])->name('admin.guru.update');
        Route::delete('/guru/{guru}', [GuruController::class, 'destroy'])->name('admin.guru.destroy');
        Route::get('/guru/export/excel', [GuruController::class, 'exportExcel'])->name('admin.guru.export.excel');
        Route::get('/guru/export/pdf', [GuruController::class, 'exportPDF'])->name('admin.guru.export.pdf');
        Route::post('/guru/import/excel', [GuruController::class, 'importExcel'])->name('admin.guru.import.excel');
        Route::get('/guru/template/download', [GuruController::class, 'downloadTemplate'])->name('admin.guru.template.download');

        // Manajemen Kunjungan
        Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('admin.kunjungan.index');
        Route::get('/kunjungan/create', [KunjunganController::class, 'create'])->name('admin.kunjungan.create');
        Route::post('/kunjungan', [KunjunganController::class, 'store'])->name('admin.kunjungan.store');
        Route::get('/kunjungan/{kunjungan}/edit', [KunjunganController::class, 'edit'])->name('admin.kunjungan.edit');
        Route::put('/kunjungan/{kunjungan}', [KunjunganController::class, 'update'])->name('admin.kunjungan.update');
        Route::delete('/kunjungan/{kunjungan}', [KunjunganController::class, 'destroy'])->name('admin.kunjungan.destroy');
        Route::get('/kunjungan/export/excel', [KunjunganController::class, 'exportExcel'])->name('admin.kunjungan.export.excel');
        Route::get('/kunjungan/export/pdf', [KunjunganController::class, 'exportPDF'])->name('admin.kunjungan.export.pdf');
        
        // Manajemen Peminjaman
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('admin.peminjaman.index');
        Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('admin.peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('admin.peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('admin.peminjaman.show');
        Route::get('/peminjaman/{peminjaman}/edit', [PeminjamanController::class, 'edit'])->name('admin.peminjaman.edit');
        Route::put('/peminjaman/{peminjaman}', [PeminjamanController::class, 'update'])->name('admin.peminjaman.update');
        Route::delete('/peminjaman/{peminjaman}', [PeminjamanController::class, 'destroy'])->name('admin.peminjaman.destroy');
        Route::get('/peminjaman/export/pdf', [PeminjamanController::class, 'exportPdf'])->name('admin.peminjaman.export.pdf');
        Route::get('/peminjaman/export/excel', [PeminjamanController::class, 'exportExcel'])->name('admin.peminjaman.export.excel');
        
        // Manajemen Pengembalian
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('admin.pengembalian.index');
        Route::get('/pengembalian/create', [PengembalianController::class, 'create'])->name('admin.pengembalian.create');
        Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('admin.pengembalian.store');
        Route::get('/pengembalian/{pengembalian}', [PengembalianController::class, 'show'])->name('admin.pengembalian.show');
        // Route::get('/pengembalian/{pengembalian}/edit', [PengembalianController::class, 'edit'])->name('admin.pengembalian.edit');
        Route::put('/pengembalian/{pengembalian}', [PengembalianController::class, 'update'])->name('admin.pengembalian.update');
        Route::delete('/pengembalian/{pengembalian}', [PengembalianController::class, 'destroy'])->name('admin.pengembalian.destroy');
        Route::get('/pengembalian/export/pdf', [PengembalianController::class, 'exportPdf'])->name('admin.pengembalian.export.pdf');
        Route::get('/pengembalian/export/excel', [PengembalianController::class, 'exportExcel'])->name('admin.pengembalian.export.excel');
    });

    // Route khusus petugas
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        // Manajemen Anggota (CR)
        Route::get('/anggota', [AnggotaController::class, 'index'])->name('anggota.index');
        Route::post('/anggota', [AnggotaController::class, 'store'])->name('anggota.store');
        Route::get('/anggota/search', [AnggotaController::class, 'search'])->name('anggota.search');
        Route::get('/anggota/{anggota}', [AnggotaController::class, 'show'])->name('anggota.show');
        
        // Manajemen Guru (CR)
        Route::get('/guru', [GuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/create', [GuruController::class, 'create'])->name('guru.create');
        Route::post('/guru', [GuruController::class, 'store'])->name('guru.store');
        Route::get('/guru/{guru}', [GuruController::class, 'show'])->name('guru.show');
        Route::get('/guru/{guru}/edit', [GuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{guru}', [GuruController::class, 'update'])->name('guru.update');
        
        // Manajemen Siswa (CR)
        Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}', [SiswaController::class, 'show'])->name('siswa.show');
        Route::get('/siswa/{siswa}/edit', [SiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
        // Kunjungan (CR)
        Route::get('/kunjungan', [KunjunganController::class, 'index'])->name('kunjungan.index');
        Route::get('/kunjungan/create', [KunjunganController::class, 'create'])->name('kunjungan.create');
        Route::post('/kunjungan', [KunjunganController::class, 'store'])->name('kunjungan.store');
        Route::get('/kunjungan/{kunjungan}', [KunjunganController::class, 'show'])->name('kunjungan.show');
        
        // Peminjaman (CR)
        Route::get('/peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.index');
        Route::get('/peminjaman/create', [PeminjamanController::class, 'create'])->name('peminjaman.create');
        Route::post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
        Route::get('/peminjaman/{peminjaman}', [PeminjamanController::class, 'show'])->name('peminjaman.show');
        
        // Pengembalian (CR)
        Route::get('/pengembalian', [PengembalianController::class, 'index'])->name('pengembalian.index');
        Route::get('/pengembalian/create', [PengembalianController::class, 'create'])->name('pengembalian.create');
        Route::post('/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
        Route::get('/pengembalian/{pengembalian}', [PengembalianController::class, 'show'])->name('pengembalian.show');

        // Buku (CR)
        Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{buku}', [BukuController::class, 'show'])->name('buku.show');
    });
});

require __DIR__.'/auth.php';

