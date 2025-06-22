<?php

namespace App\Imports;

use App\Models\Kunjungan;
use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class KunjunganImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Find SIswa by NIS
        $siswa = Siswa::where('nis', $row['nis'])->first();
        
        if (!$siswa) {
            throw new \Exception("Siswa dengan NIS {$row['nis']} tidak ditemukan");
        }

        return new Kunjungan([
            'siswa_id' => $siswa->id,
            'tanggal_kunjungan' => $row['tanggal_kunjungan'],
            'keterangan' => $row['keterangan'],
        ]);
    }

    public function rules(): array
    {
        return [
            'nis' => 'required|exists:siswas,nis',
            'tanggal_kunjungan' => 'required|date',
            'keterangan' => 'required|string',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'nis.required' => 'NIS siswa harus diisi',
            'nis.exists' => 'NIS siswa tidak ditemukan',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan harus diisi',
            'tanggal_kunjungan.date' => 'Format tanggal kunjungan tidak valid',
            'keterangan.required' => 'Keterangan harus diisi',
        ];
    }
}
