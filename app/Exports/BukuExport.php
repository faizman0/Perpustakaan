<?php

namespace App\Exports;

use App\Models\Buku;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BukuExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    public function collection()
    {
        return Buku::with('kategori')->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kategori',
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

    public function map($buku): array
    {
        static $index = 0;
        $index++;
        
        return [
            $index,
            $buku->kategori->nama,
            $buku->judul,
            $buku->no_inventaris,
            $buku->no_klasifikasi,
            $buku->pengarang,
            $buku->penerbit,
            $buku->tahun_terbit,
            $buku->edisi ?? '-',
            $buku->isbn ?? '-',
            $buku->kolase ?? '-',
            $buku->jumlah,
            $buku->keterangan ?? '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ],
            'A:M' => [
                'alignment' => ['vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER]
            ],
            'A' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'G' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
            'K' => ['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,  // No
            'B' => 20, // Kategori
            'C' => 40, // Judul
            'D' => 15, // No. Inventaris
            'E' => 15, // No. Klasifikasi
            'F' => 20, // Pengarang
            'G' => 20, // Penerbit
            'H' => 12, // Tahun Terbit
            'I' => 10, // Edisi
            'J' => 15, // ISBN
            'K' => 15, // Kolase
            'L' => 10, // Jumlah
            'M' => 30, // Keterangan
        ];
    }

    public function title(): string
    {
        return 'Data Buku';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Auto-size columns based on content
                foreach(range('A','M') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
                
                // Add filters
                $event->sheet->setAutoFilter('A1:M1');
                
                // Add borders
                $event->sheet->getStyle('A1:M' . ($event->sheet->getHighestRow()))->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                ]);
            },
        ];
    }
}