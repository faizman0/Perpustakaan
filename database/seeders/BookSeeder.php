<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BookSeeder extends Seeder
{
    public function run()
    {
        // Ambil kategori
        $kimia = Category::where('name', 'Kimia')->first();
        $abc = Category::where('name', 'ABC')->first();

        // Data buku kimia
        $chemistryBooks = [
            [
                'title' => 'Kimia Dasar 1',
                'author' => 'Petrucci',
                'isbn' => '978-602-425-011-1',
                'publisher' => 'Erlangga',
                'year' => 2020,
                'category_id' => $kimia->id,
                'stock' => 10,
            ],
            [
                'title' => 'Kimia Organik',
                'author' => 'John McMurry',
                'isbn' => '978-602-425-022-2',
                'publisher' => 'Penerbit ITB',
                'year' => 2019,
                'category_id' => $kimia->id,
                'stock' => 8,
            ],
            [
                'title' => 'Kimia Analitik',
                'author' => 'Gary D. Christian',
                'isbn' => '978-602-425-033-3',
                'publisher' => 'Gramedia',
                'year' => 2021,
                'category_id' => $kimia->id,
                'stock' => 5,
            ],
        ];

        // Data buku ABC
        $abcBooks = [
            [
                'title' => 'ABC Belajar Membaca',
                'author' => 'Tim ABC',
                'isbn' => '978-602-425-044-4',
                'publisher' => 'Pustaka ABC',
                'year' => 2022,
                'category_id' => $abc->id,
                'stock' => 15,
            ],
            [
                'title' => 'ABC Matematika Dasar',
                'author' => 'Prof. ABC',
                'isbn' => '978-602-425-055-5',
                'publisher' => 'Media ABC',
                'year' => 2021,
                'category_id' => $abc->id,
                'stock' => 12,
            ],
            [
                'title' => 'Panduan ABC untuk Pemula',
                'author' => 'Dr. ABC',
                'isbn' => '978-602-425-066-6',
                'publisher' => 'ABC Press',
                'year' => 2020,
                'category_id' => $abc->id,
                'stock' => 7,
            ],
        ];

        // Gabungkan semua buku
        $allBooks = array_merge($chemistryBooks, $abcBooks);

        // Tambahkan beberapa buku umum
        $allBooks[] = [
            'title' => 'Panduan Laravel untuk Pemula',
            'author' => 'Laravel Expert',
            'isbn' => '978-602-425-077-7',
            'publisher' => 'Programmer Press',
            'year' => 2023,
            'category_id' => Category::where('name', '!=', 'Kimia')
                                ->where('name', '!=', 'ABC')
                                ->first()->id,
            'stock' => 20,
        ];

        foreach ($allBooks as $book) {
            Book::create($book);
        }
    }
}