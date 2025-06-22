<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class GuruTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithColumnFormatting
{
    public function array(): array
    {
        // Contoh data
        return [
            [
                'Putra',
                '22345',
                'Laki-laki'
            ],
            [
                'Putri',
                '12345',
                'Perempuan'
            ],
            [
                'Muhammad Putra',
                '3345',
                'L'
            ],
            [
                'Putri Ayu',
                '44456',
                'P'
            ]
        ];
    }

    public function headings(): array
    {
        return [
            'Nama',
            'NIP',
            'Jenis Kelamin'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row
            'A1:C1' => [
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
            'A2:C3' => [
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
            'B' => NumberFormat::FORMAT_TEXT, // Format NIP sebagai text
        ];
    }
} 