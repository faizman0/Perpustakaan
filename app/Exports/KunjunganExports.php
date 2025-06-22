<?php

namespace App\Exports;

use App\Models\Kunjungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class KunjunganExports implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate = null, $endDate = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Kunjungan::with(['anggota.siswa', 'anggota.guru']);

        if ($this->startDate && $this->endDate) {
            $query->whereBetween('tanggal_kunjungan', [$this->startDate, $this->endDate]);
        }

        return $query->latest()->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Kode Anggota',
            'Nama',
            'Tipe',
            'NIS/NIP',
            'Tanggal Kunjungan',
            'Keterangan'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4F81BD']
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    ],
                ],
            ],
        ];
    }

    public function map($kunjungan): array
    {
        static $index = 0;
        $index++;

        $kodeAnggota = $kunjungan->anggota ? $kunjungan->anggota->kode_anggota : '-';
        $nama = $kunjungan->anggota ? $kunjungan->anggota->nama : 'Data Anggota Tidak Ditemukan';
        $tipe = $kunjungan->anggota ? $kunjungan->anggota->tipe : '-';
        
        $nisNip = '-';
        if ($kunjungan->anggota) {
            if ($kunjungan->anggota->siswa) {
                $nisNip = $kunjungan->anggota->siswa->nis;
            } elseif ($kunjungan->anggota->guru) {
                $nisNip = $kunjungan->anggota->guru->nip;
            }
        }

        return [
            $index,
            $kodeAnggota,
            $nama,
            $tipe,
            $nisNip,
            $kunjungan->tanggal_kunjungan,
            $kunjungan->keterangan ?? '-'
        ];
    }
}
