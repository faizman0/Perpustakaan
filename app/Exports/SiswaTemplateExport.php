<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class SiswaTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    public function array(): array
    {
        // Contoh data
        return [
            [
                'Upin',
                '123',
                'Laki-laki',
                'I'
            ],
            [
                'Mei Mei',
                '066',
                'Perempuan',
                'II'
            ],
            [
                'Muhammad Upin',
                '133',
                'L',
                'III'
            ],
            [
                'Susanti',
                '0777',
                'P',
                'IV'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIS',
            'Jenis Kelamin',
            'Kelas'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row
            'A1:D1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
            'A2:D3' => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_TEXT, // Format NIS sebagai text
        ];
    }
} 