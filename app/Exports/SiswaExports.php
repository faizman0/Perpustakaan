<?php

namespace App\Exports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExports implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Siswa::all();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIS',
            'Jenis Kelamin',
            'Kelas',
        ];
    }

    public function map($siswa): array
    {
        return [
            $siswa->nama,
            $siswa->jenis_kelamin,
            $siswa->kelas,
            $siswa->nis,
        ];
    }
}
