<?php

namespace App\Imports;

use App\Models\Guru;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Facades\Log;

class GuruImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError
{
    use SkipsErrors;

    private $importErrors = [];
    private $rowNumber = 0;
    private $successCount = 0;
    private $errorCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $this->rowNumber++;

        try {
            // Normalize column names
            $normalizedRow = $this->normalizeColumnNames($row);

            // Validate required fields
            $requiredFields = ['nama', 'nip', 'jenis_kelamin'];
            foreach ($requiredFields as $field) {
                if (empty($normalizedRow[$field])) {
                    throw new \Exception("Kolom {$field} wajib diisi");
                }
            }

            // Convert L/P to full text
            if (isset($normalizedRow['jenis_kelamin'])) {
                $normalizedRow['jenis_kelamin'] = $this->normalizeJenisKelamin($normalizedRow['jenis_kelamin']);
            }

            // Check if NIP already exists
            $existingGuru = Guru::where('nip', trim($normalizedRow['nip']))->first();
            if ($existingGuru) {
                throw new \Exception("NIP {$normalizedRow['nip']} sudah terdaftar");
            }

            $guru = new Guru([
                'nama' => trim($normalizedRow['nama']),
                'nip' => trim($normalizedRow['nip']),
                'jenis_kelamin' => trim($normalizedRow['jenis_kelamin']),
            ]);

            $this->successCount++;
            return $guru;

        } catch (\Exception $e) {
            $this->errorCount++;
            $this->importErrors[] = [
                'row' => $this->rowNumber,
                'message' => $e->getMessage(),
                'values' => $row
            ];
            Log::error("Error importing guru pada baris {$this->rowNumber}: " . $e->getMessage());
            return null;
        }
    }

    private function normalizeJenisKelamin($value)
    {
        $value = strtoupper(trim($value));
        if ($value === 'L') {
            return 'Laki-laki';
        } elseif ($value === 'P') {
            return 'Perempuan';
        }
        return $value;
    }

    private function normalizeColumnNames(array $row): array
    {
        $normalized = [];
        $columnMapping = [
            'nama' => ['nama', 'name', 'nama_guru', 'Nama', 'Nama Guru'],
            'nip' => ['nip', 'nomor_induk', 'nomor_induk_guru', 'NIP', 'Nomor Induk'],
            'jenis_kelamin' => ['jenis_kelamin', 'gender', 'jk', 'Jenis Kelamin', 'JK']
        ];

        foreach ($row as $key => $value) {
            $normalizedKey = strtolower(trim($key));
            $normalizedKey = str_replace([' ', '.', '-'], '_', $normalizedKey);
            
            foreach ($columnMapping as $standardKey => $possibleKeys) {
                if (in_array($normalizedKey, $possibleKeys)) {
                    $normalized[$standardKey] = is_string($value) ? trim($value) : $value;
                    break;
                }
            }
        }
        return $normalized;
    }

    public function rules(): array
    {
        return [
            '*.nama' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            '*.nip' => 'required|numeric',
            '*.jenis_kelamin' => 'required|in:Laki-laki,Perempuan,L,P',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama.required' => 'Kolom nama wajib diisi',
            '*.nama.regex' => 'Nama hanya boleh berisi huruf dan spasi',
            '*.nip.required' => 'Kolom NIP wajib diisi',
            '*.nip.numeric' => 'Kolom NIP harus berupa angka',
            '*.jenis_kelamin.required' => 'Kolom jenis kelamin wajib diisi',
            '*.jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki, Perempuan, L, atau P',
        ];
    }

    /**
     * Handle import failures
     */
    public function onFailure(\Maatwebsite\Excel\Validators\Failure ...$failures)
    {
        foreach ($failures as $failure) {
            $this->errorCount++;
            $this->importErrors[] = [
                'row' => $failure->row(),
                'message' => implode(', ', $failure->errors()),
                'values' => $failure->values()
            ];
            Log::error("Validation error at row {$failure->row()}: " . implode(', ', $failure->errors()));
        }
    }

    /**
     * Get collected errors
     */
    public function getErrors()
    {
        return $this->importErrors;
    }

    /**
     * Get success count
     */
    public function getSuccessCount()
    {
        return $this->successCount;
    }

    /**
     * Get error count
     */
    public function getErrorCount()
    {
        return $this->errorCount;
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
