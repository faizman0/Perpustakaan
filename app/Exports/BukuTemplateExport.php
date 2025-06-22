<?php

namespace App\Exports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BukuTemplateExport implements WithHeadings, WithStyles, WithColumnWidths, WithTitle
{
    public function headings(): array
    {
        return [
            'Kategori (Nama Kategori)',
            'Judul',
            'No. Inventaris',
            'No. Klasifikasi',
            'Pengarang',
            'Penerbit',
            'Tahun Terbit',
            'Edisi',
            'ISBN',
            'Kolase',
            'Jumlah',
            'Keterangan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2EFDA']
            ]
        ]);

        $sheet->getStyle('A:L')->applyFromArray([
            'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]
        ]);

        return [];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30, // Kategori
            'B' => 40, // Judul
            'C' => 15, // No. Inventaris
            'D' => 15, // No. Klasifikasi
            'E' => 20, // Pengarang
            'F' => 20, // Penerbit
            'G' => 12, // Tahun Terbit
            'H' => 10, // Edisi
            'I' => 15, // ISBN
            'J' => 15, // Kolase
            'K' => 10, // Jumlah
            'L' => 30, // Keterangan
        ];
    }

    public function title(): string
    {
        return 'Template Import Buku';
    }
} 