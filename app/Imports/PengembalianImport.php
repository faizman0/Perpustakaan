<?php

namespace App\Imports;

use App\Models\Pengembalian;
use Maatwebsite\Excel\Concerns\ToModel;

class PengembalianImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Pengembalian([
            'peminjaman_id' => $row[0],
            'tanggal_kembali' => $row[1],
        ]);
    }
}
