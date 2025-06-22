<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PeminjamanExport implements FromCollection, WithHeadings, WithMapping
{
    protected $peminjamans;

    public function __construct($peminjamans)
    {
        $this->peminjamans = $peminjamans;
    }

    public function collection()
    {
        return $this->peminjamans;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Anggota',
            'Nama',
            'Tipe',
            'NIS/NIP',
            'Judul Buku',
            'Kategori',
            'Tanggal Pinjam',
            'Tanggal Pengembalian',
            'Status'
        ];
    }

    public function map($peminjaman): array
    {
        static $rowNumber = 0;
        $rowNumber++;

        $nisNip = '-';
        if ($peminjaman->anggota) {
            if ($peminjaman->anggota->siswa) {
                $nisNip = $peminjaman->anggota->siswa->nis;
            } elseif ($peminjaman->anggota->guru) {
                $nisNip = $peminjaman->anggota->guru->nip;
            }
        }

        $status = $peminjaman->pengembalian ? 'Dikembalikan' : 'Dipinjam';

        return [
            $rowNumber,
            $peminjaman->anggota->kode_anggota,
            $peminjaman->anggota->nama,
            $peminjaman->anggota->tipe,
            $nisNip,
            $peminjaman->buku->judul,
            $peminjaman->buku->kategori->nama,
            $peminjaman->tanggal_peminjaman,
            $peminjaman->tanggal_pengembalian,
            $status
        ];
    }
}
