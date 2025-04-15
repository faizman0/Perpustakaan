<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\BookReturnController;
use App\Http\Controllers\ReportController;

Route::middleware('auth')->group(function () {
    Route::resource('members', MemberController::class);
    Route::resource('books', BookController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('borrowings', BorrowingController::class);
    Route::put('/borrowings/{borrowing}/return', 
    [BorrowingController::class, 'returnBook'])->name('borrowings.return');

    Route::get('/members/export/excel', [MemberController::class, 'exportExcel'])->name('members.export.excel');
    Route::get('/members/export/pdf', [MemberController::class, 'exportPDF'])->name('members.export.pdf');
    Route::post('/members/import/excel', [MemberController::class, 'importExcel'])->name('members.import.excel');

});

// Rute untuk tamu (belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/ceklogin', [AuthController::class, 'login'])->name('ceklogin');
});

// Rute untuk pengguna yang sudah login
Route::middleware('auth')->group(function () {
    Route::redirect('/', '/dashboard');
    Route::get('/dashboard', [PageController::class, 'dashboard'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('/reports/monthly-borrowings', [ReportController::class, 'monthlyBorrowings'])->name('reports.monthly-borrowings');
    Route::get('/reports/late-returns', [ReportController::class, 'lateReturns'])->name('reports.late-returns');
    Route::get('/reports/book-stock', [ReportController::class, 'bookStock'])->name('reports.book-stock');
    Route::get('/reports/active-members', [ReportController::class, 'activeMembers'])->name('reports.active-members');
    Route::get('/reports/popular-books', [ReportController::class, 'popularBooks'])->name('reports.popular-books');
    Route::get('/reports/fines', [ReportController::class, 'fines'])->name('reports.fines');
});