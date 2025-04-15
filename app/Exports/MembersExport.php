<?php

namespace App\Exports;

use App\Models\Member;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MembersExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Member::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'NIS',
            'Kelas',
            'Tanggal Dibuat',
            'Tanggal Diupdate'
        ];
    }

    public function map($member): array
    {
        return [
            $member->id,
            $member->name,
            $member->nis,
            $member->class,
            $member->created_at->format('d-m-Y H:i:s'),
            $member->updated_at->format('d-m-Y H:i:s')
        ];
    }
}