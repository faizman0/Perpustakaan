<?php

namespace App\Exports;

use App\Models\Pengembalian;
use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class PengembalianExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithEvents
{
    protected $pengembalians;

    public function __construct($pengembalians)
    {
        $this->pengembalians = $pengembalians;
    }

    public function collection()
    {
        return $this->pengembalians;
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Anggota',
            'Peminjam',
            'NIS/NIP',
            'Tipe',
            'Kelas',
            'Buku',
            'Tanggal Pinjam',
            'Status',
            'Tanggal Kembali'
        ];
    }

    public function map($pengembalian): array
    {
        static $index = 0;
        $index++;

        $anggota = $pengembalian->peminjaman->anggota;
        $nis_nip = $anggota->siswa ? $anggota->siswa->nis : ($anggota->guru ? $anggota->guru->nip : '-');
        $kelas = $anggota->siswa ? $anggota->siswa->kelas->nama_kelas : '-';
        $tipe = $anggota->tipe;

        // Calculate late status
        $tanggalKembali = \Carbon\Carbon::parse($pengembalian->tanggal_kembali);
        $tanggalPinjam = \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam);
        $jatuhTempo = $tanggalPinjam->addDays(7);
        $terlambat = $tanggalKembali->diffInDays($jatuhTempo, false);
        
        $status = 'Dikembalikan';
        if ($terlambat < 0) {
            $status .= ' (Terlambat ' . abs($terlambat) . ' hari)';
        }

        return [
            $index,
            $anggota->kode_anggota,
            $anggota->nama,
            $nis_nip,
            $tipe,
            $kelas,
            $pengembalian->peminjaman->buku->judul,
            $pengembalian->peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($pengembalian->peminjaman->tanggal_pinjam)->format('d/m/Y') : '-',
            $status,
            $pengembalian->tanggal_kembali ? \Carbon\Carbon::parse($pengembalian->tanggal_kembali)->format('d/m/Y') : '-'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
            'A1:J1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet;
                
                // Add borders to all cells
                $lastRow = $sheet->getHighestRow();
                $sheet->getStyle('A1:J' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
                
                // Center align all cells
                $sheet->getStyle('A1:J' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                
                // Left align for Peminjam and Buku columns
                $sheet->getStyle('C1:C' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('G1:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            },
        ];
    }
} 