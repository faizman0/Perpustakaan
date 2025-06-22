<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class KunjunganExports implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Kunjungan::with(['siswa.kelas', 'guru']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_kunjungan', [$this->startDate, $this->endDate]);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Kunjungan',
            'NIS/NIP',
            'Nama Pengunjung',
            'Tipe Pengunjung',
            'Kelas/Bidang Studi',
            'Keterangan'
        ];
    }

    public function map($kunjungan): array
    {
        static $index = 0;
        $index++;

        if ($kunjungan->siswa_id) {
            $nis = $kunjungan->siswa ? $kunjungan->siswa->nis : '-';
            $nama = $kunjungan->siswa ? $kunjungan->siswa->nama : 'Data Siswa Tidak Ditemukan';
            $tipe = 'Siswa';
            $kelas = $kunjungan->siswa && $kunjungan->siswa->kelas ? $kunjungan->siswa->kelas->nama_kelas : '-';
        } else {
            $nis = $kunjungan->guru ? $kunjungan->guru->nip : '-';
            $nama = $kunjungan->guru ? $kunjungan->guru->nama : 'Data Guru Tidak Ditemukan';
            $tipe = 'Guru';
            $kelas = $kunjungan->guru ? $kunjungan->guru->bidang_studi : '-';
        }

        return [
            $index,
            $kunjungan->tanggal_kunjungan,
            $nis,
            $nama,
            $tipe,
            $kelas,
            $kunjungan->keterangan ?? '-'
        ];
    }
}
