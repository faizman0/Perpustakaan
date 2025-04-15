<?php

namespace App\Http\Controllers;

use App\Models\BookReturn;
use App\Models\Borrowing;
use Illuminate\Http\Request;

class BookReturnController extends Controller
{
    public function index()
    {
        $bookReturns = BookReturn::with('borrowing.member', 'borrowing.book')->get();
        return view('book-returns.index',[
            'bookReturns' => $bookReturns, 'key',
            'key' => 'bookReturn'
        ]);
    }

    public function create()
    {
        $borrowings = Borrowing::with('member', 'book')->get();
        return view('book-returns.create',[
            'borrowings' => $borrowings,
            'key' => 'bookReturn'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'return_date' => 'required|date',
            'fine' => 'nullable|integer|min:0',
        ]);

        // Ambil data peminjaman
        $borrowing = \App\Models\Borrowing::find($request->borrowing_id);

        // Tambah stok buku yang dikembalikan
        $book = $borrowing->book;
        $book->increment('stock');

        // Simpan data pengembalian
        \App\Models\BookReturn::create($request->all());

        return redirect()->route('book-returns.index')->with('success', 'Pengembalian berhasil.');
    }

    public function edit(BookReturn $bookReturn)
    {
        $borrowings = Borrowing::with('member', 'book')->get();
        return view('book-returns.edit', compact('bookReturn', 'borrowings'));
    }

    public function update(Request $request, BookReturn $bookReturn)
    {
        $request->validate([
            'borrowing_id' => 'required|exists:borrowings,id',
            'return_date' => 'required|date',
            'fine' => 'required|integer|min:0',
        ]);

        $bookReturn->update($request->all());
        return redirect()->route('book-returns.index')->with('success', 'Book return updated successfully.');
    }

    public function destroy(BookReturn $bookReturn)
    {
        $bookReturn->delete();
        return redirect()->route('book-returns.index')->with('success', 'Book return deleted successfully.');
    }
}