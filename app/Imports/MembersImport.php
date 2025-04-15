<?php

namespace App\Imports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MembersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Member([
            'name' => $row['nama'],
            'nis' => $row['nis'],
            'class' => $row['kelas'],
        ]);
    }
}