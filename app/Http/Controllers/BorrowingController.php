<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Member;
use App\Models\Book;
use Illuminate\Http\Request;

class BorrowingController extends Controller
{
    public function index()
    {
        $borrowings = Borrowing::with(['member', 'book'])->get();
        return view('borrowings.index', [
            'borrowings' => $borrowings,
            'key' => 'borrowing'
        ]);
    }

    public function create()
    {
        $members = Member::all();
        $books = Book::where('stock', '>', 0)->get(); // Hanya tampilkan buku yang tersedia
        return view('borrowings.create', [
            'members' => $members,
            'books' => $books,
            'key' => 'borrowing',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_ids' => 'required|array',
            'book_ids.*' => 'exists:books,id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date',
        ]);

        // Cek stok untuk semua buku yang dipinjam
        $books = Book::whereIn('id', $request->book_ids)->get();
        
        foreach ($books as $book) {
            if ($book->stock < 1) {
                return redirect()->back()->with('error', 'Stok buku "'.$book->title.'" habis.');
            }
        }

        // Proses peminjaman untuk setiap buku
        foreach ($request->book_ids as $book_id) {
            $book = Book::find($book_id);
            
            // Kurangi stok buku
            $book->decrement('stock');
            
            // Simpan data peminjaman untuk setiap buku
            Borrowing::create([
                'member_id' => $request->member_id,
                'book_id' => $book_id,
                'borrow_date' => $request->borrow_date,
                'return_date' => $request->return_date,
            ]);
        }

        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman '.count($request->book_ids).' buku berhasil.');
    }

    public function edit(Borrowing $borrowing)
    {
        $members = Member::all();
        $books = Book::all();
        return view('borrowings.edit', compact('borrowing', 'members', 'books'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'return_date' => 'nullable|date',
        ]);

        // Jika buku yang dipinjam berubah
        if ($borrowing->book_id != $request->book_id) {
            // Kembalikan stok buku sebelumnya
            $previousBook = Book::find($borrowing->book_id);
            $previousBook->increment('stock');

            // Kurangi stok buku baru
            $newBook = Book::find($request->book_id);
            if ($newBook->stock < 1) {
                return redirect()->back()->with('error', 'Stok buku habis.');
            }
            $newBook->decrement('stock');
        }

        $borrowing->update($request->all());

        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(Borrowing $borrowing)
    {
        // Kembalikan stok buku sebelum menghapus
        $book = $borrowing->book;
        $book->increment('stock');
        
        $borrowing->delete();
        return redirect()->route('borrowings.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        // Pastikan buku belum dikembalikan
        if ($borrowing->return_date) {
            return redirect()->route('borrowings.index')
                ->with('error', 'Buku sudah dikembalikan.');
        }

        // Tambah stok buku yang dikembalikan
        $book = $borrowing->book;
        $book->increment('stock');

        // Update tanggal pengembalian
        $borrowing->update([
            'return_date' => now(),
        ]);

        return redirect()->route('borrowings.index')
            ->with('success', 'Buku berhasil dikembalikan.');
    }
}