<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $peminjamanSiswa;
    protected $peminjamanGuru;

    public function __construct($peminjamanSiswa, $peminjamanGuru)
    {
        $this->peminjamanSiswa = $peminjamanSiswa;
        $this->peminjamanGuru = $peminjamanGuru;
    }

    public function collection()
    {
        return $this->peminjamanSiswa->concat($this->peminjamanGuru);
    }

    public function headings(): array
    {
        return [
            'No',
            'Tipe Peminjam',
            'Nama Peminjam',
            'Kelas',
            'Judul Buku',
            'Kategori',
            'Tanggal Pinjam',
            'Tanggal Kembali',
            'Status'
        ];
    }

    public function map($peminjaman): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $peminjam = $peminjaman->siswa_id ? $peminjaman->siswa : $peminjaman->guru;
        $kelas = $peminjaman->siswa_id ? $peminjaman->siswa->kelas->nama_kelas : '-';
        $status = $peminjaman->tanggal_kembali ? 'Dikembalikan' : 'Dipinjam';

        return [
            $rowNumber,
            $peminjaman->siswa_id ? 'Siswa' : 'Guru',
            $peminjam->nama,
            $kelas,
            $peminjaman->buku->judul,
            $peminjaman->buku->kategori->nama,
            $peminjaman->tanggal_pinjam,
            $peminjaman->tanggal_kembali ?? '-',
            $status
        ];
    }
}
