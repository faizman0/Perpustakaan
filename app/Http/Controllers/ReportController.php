<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Member;
use App\Models\Book;

class ReportController extends Controller
{
    // Laporan Peminjaman Bulanan
    public function monthlyBorrowings()
    {
        $reports = Borrowing::selectRaw('YEAR(borrow_date) as year, MONTH(borrow_date) as month, COUNT(*) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('reports.monthly-borrowings', [
            'borrowings' => $reports,
            'key' => 'report'
        ]);
    }

    // Laporan Pengembalian Terlambat
    public function lateReturns()
    {
        $lateReturns = Borrowing::whereNotNull('return_date')
            ->whereRaw('return_date > DATE_ADD(borrow_date, INTERVAL 7 DAY)') // Contoh: batas pengembalian 7 hari
            ->get();

        return view('reports.late-returns', [
            'lateReturns' => $lateReturns,
            'key' => 'report'
        ]);
    }

    // Laporan Stok Buku
    public function bookStock()
    {
        $books = Book::with('category')->get();
        return view('reports.book-stock', compact('books'));
    }

    // Laporan Anggota Teraktif
    public function activeMembers()
    {
        $activeMembers = Member::withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->get();

        return view('reports.active-members', compact('activeMembers'));
    }

    // Laporan Buku Terpopuler
    public function popularBooks()
    {
        $popularBooks = Book::withCount('borrowings')
            ->orderBy('borrowings_count', 'desc')
            ->get();

        return view('reports.popular-books', compact('popularBooks'));
    }

    // Laporan Denda
    public function fines()
    {
        $fines = Borrowing::whereNotNull('fine')
            ->selectRaw('YEAR(return_date) as year, MONTH(return_date) as month, SUM(fine) as total_fine')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        return view('reports.fines', compact('fines'));
    }
}
