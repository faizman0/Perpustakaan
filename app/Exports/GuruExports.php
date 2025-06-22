<?php

namespace App\Exports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\FromCollection;

class GuruExports implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Guru::all();
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIP',
            'Jenis Kelamin',
        ];
    }

    public function map($guru): array
    {
        return [
            $guru->nama,
            $guru->nip,
            $guru->jenis_kelamin,
        ];
    }
    
}
