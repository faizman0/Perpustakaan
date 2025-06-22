<?php

namespace App\Imports;

use App\Models\Buku;
use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Validation\Rules;
use Illuminate\Support\Collection;

class BukuImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    private $errors = [];
    private $rowNumber = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->rowNumber++;

        try {
            // Debug: Log the incoming row data
            // \Log::info('Processing row: ' . json_encode($row));

            // Normalisasi nama kolom
            $normalizedRow = $this->normalizeColumnNames($row);

            // Debug: Log normalized data and available keys
            \Log::info('Normalized row keys: ' . json_encode(array_keys($normalizedRow)));
            \Log::info('Normalized row values: ' . json_encode($normalizedRow));

            // Check if 'kategori' key exists and is not empty after normalization
            $kategoriValue = $normalizedRow['kategori'] ?? ($row['kategori'] ?? null);
            if (empty(trim($kategoriValue))) {
                 // If still empty, check original case if available and log
                 $originalKategori = $row['Kategori'] ?? null;
                 if(!empty(trim($originalKategori))){
                     $kategoriValue = $originalKategori;
                     \Log::warning('Used original case for Kategori: ' . $kategoriValue);
                 } else {
                     throw new \Exception('Kolom kategori wajib diisi');
                 }
            }
            
            $kategoriValue = trim($kategoriValue);

            // Cari kategori, jika tidak ada buat baru
            $kategori = Kategori::firstOrCreate(
                ['nama' => $kategoriValue],
                ['keterangan' => 'Imported from Excel']
            );

            // Konversi no_klasifikasi ke string agar validasi tidak gagal jika Excel mengirim angka
            if (isset($normalizedRow['no_klasifikasi'])) {
                $normalizedRow['no_klasifikasi'] = (string)trim($normalizedRow['no_klasifikasi']);
            }
            // Konversi edisi ke string agar validasi tidak gagal jika Excel mengirim angka
            if (isset($normalizedRow['edisi'])) {
                $normalizedRow['edisi'] = (string)trim($normalizedRow['edisi']);
            }

            // Validasi data wajib menggunakan normalized keys
            $requiredFields = ['judul', 'no_inventaris', 'no_klasifikasi', 'pengarang', 'penerbit', 'tahun_terbit', 'jumlah'];
            foreach ($requiredFields as $field) {
                if (empty($normalizedRow[$field])) {
                    throw new \Exception("Kolom {$field} wajib diisi");
                }
            }

            return new Buku([
                'kategori_id'       => $kategori->id,
                'judul'             => trim($normalizedRow['judul']),
                'no_inventaris'     => trim($normalizedRow['no_inventaris']),
                'no_klasifikasi'    => trim($normalizedRow['no_klasifikasi']),
                'pengarang'         => trim($normalizedRow['pengarang']),
                'penerbit'          => trim($normalizedRow['penerbit']),
                'tahun_terbit'      => (int)$normalizedRow['tahun_terbit'],
                'edisi'             => $normalizedRow['edisi'] ?? null,
                'isbn'              => $normalizedRow['isbn'] ?? null,
                'kolase'            => $normalizedRow['kolase'] ?? null,
                'jumlah'            => (int)$normalizedRow['jumlah'],
                'keterangan'        => $normalizedRow['keterangan'] ?? null,
            ]);
        } catch (\Exception $e) {
            $this->errors[] = [
                'row' => $this->rowNumber,
                'message' => $e->getMessage(),
                'values' => $row // Log original row data
            ];
            return null;
        }
    }

    private function normalizeColumnNames(array $row): array
    {
        $normalized = [];
        $columnMapping = [
            'kategori' => ['kategori', 'kategori_id', 'nama_kategori'],
            'judul' => ['judul', 'title'],
            'no_inventaris' => ['no_inventaris', 'no_inv', 'inventaris'],
            'no_klasifikasi' => ['no_klasifikasi', 'no_klas', 'klasifikasi'],
            'pengarang' => ['pengarang', 'author'],
            'penerbit' => ['penerbit', 'publisher'],
            'tahun_terbit' => ['tahun_terbit', 'tahun', 'year'],
            'edisi' => ['edisi', 'edition'],
            'isbn' => ['isbn'],
            'kolase' => ['kolase', 'collation'],
            'jumlah' => ['jumlah', 'qty', 'quantity'],
            'keterangan' => ['keterangan', 'note', 'description']
        ];

        foreach ($row as $key => $value) {
            // Normalize key: lowercase, replace spaces/dots/hyphens with underscores
            $normalizedKey = strtolower(trim($key));
            $normalizedKey = str_replace([' ', '.', '-'], '_', $normalizedKey);
            
            // Find matching standard key using mapping
            foreach ($columnMapping as $standardKey => $possibleKeys) {
                if (in_array($normalizedKey, $possibleKeys)) {
                     // Use the standard key in the normalized row
                    $normalized[$standardKey] = is_string($value) ? trim($value) : $value;
                    // Break inner loop once mapping is found for this original key
                    break;
                }
            }
        }
        return $normalized;
    }

    public function rules(): array
    {
        return [
            '*.kategori'       => 'required|string|max:100',
            '*.judul'          => 'required|max:255',
            '*.no_inventaris'  => 'required|unique:bukus,no_inventaris',
            '*.no_klasifikasi' => 'nullable|string',
            '*.pengarang'      => 'required|max:100',
            '*.penerbit'       => 'required|max:100',
            '*.tahun_terbit'   => 'required|integer|min:1900|max:'.(date('Y')+1),
            '*.edisi'          => 'nullable|string|max:50',
            '*.isbn'           => 'nullable|string|max:20|unique:bukus,isbn',
            '*.kolase'         => 'nullable|string|max:50',
            '*.jumlah'         => 'required|integer|min:1',
            '*.keterangan'     => 'nullable|string',
        ];
    }

    /**
     * Custom validation messages
     */
    public function customValidationMessages()
    {
        return [
            '*.kategori.required' => 'Kolom kategori wajib diisi',
            '*.judul.required' => 'Kolom judul wajib diisi',
            '*.no_inventaris.required' => 'Kolom nomor inventaris wajib diisi',
            '*.no_inventaris.unique' => 'Nomor inventaris sudah digunakan',
            '*.pengarang.required' => 'Kolom pengarang wajib diisi',
            '*.penerbit.required' => 'Kolom penerbit wajib diisi',
            '*.tahun_terbit.required' => 'Kolom tahun terbit wajib diisi',
            '*.tahun_terbit.max' => 'Tahun terbit maksimal ' . (date('Y') + 1),
            '*.jumlah.required' => 'Kolom jumlah wajib diisi',
            '*.jumlah.integer' => 'Jumlah harus berupa angka',
            '*.jumlah.min' => 'Jumlah minimal 1',
        ];
    }

    /**
     * Handle import failures
     */
    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errors[] = [
                'row' => $failure->row(),
                'message' => implode(', ', $failure->errors()),
                'values' => $failure->values()
            ];
        }
    }

    /**
     * Get collected errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Set batch size
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * Set chunk size
     */
    public function chunkSize(): int
    {
        return 100;
    }
}