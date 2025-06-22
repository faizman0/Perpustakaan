<?php

namespace App\Imports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\Kategori;

class PeminjamanImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $kategori = Kategori::firstOrCreate([
            'nama' => $row['kategori']
        ]);
        return new Peminjaman([
            'member_id' => $row['anggota'],
            'book_id' => $row['buku'],
            'borrow_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_pinjam']),
            'return_date' => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['tanggal_kembali']),
        ]);
    }

    public function rules(): array
    {
        return [
            'anggota' => 'required|exists:members,id',
            'buku' => 'required|exists:books,id',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali' => 'nullable|date',
        ];
    }
    public function headingRow(): int
    {
        return 1; // Assuming the first row contains the headings
    }
    public function uniqueBy(): string
    {
        return 'member_id';
    }
    public function chunkSize(): int
    {
        return 1000; // Adjust the chunk size as needed
    }
    public function startRow(): int
    {
        return 2; // Assuming the first row contains the headings
    }
    public function heading(): array
    {
        return [
            'ID Anggota',
            'ID Buku',
            'Tanggal Pinjam',
            'Tanggal Kembali',
        ];
    }
    public function map($peminjaman): array
    {
        return [
            $peminjaman->anggota_id,
            $peminjaman->buku_id,
            $peminjaman->tanggal_pinjam->format('d-m-Y'),
            $peminjaman->tanggal_kembali ? $peminjaman->tanggal_kembali->format('d-m-Y') : 'Belum Kembali',
        ];
    }
}
