<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading, SkipsOnError
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
            $requiredFields = ['nama', 'nis', 'jenis_kelamin', 'kelas'];
            foreach ($requiredFields as $field) {
                if (empty($normalizedRow[$field])) {
                    throw new \Exception("Kolom {$field} wajib diisi");
                }
            }

            // Convert L/P to full text
            if (isset($normalizedRow['jenis_kelamin'])) {
                $normalizedRow['jenis_kelamin'] = $this->normalizeJenisKelamin($normalizedRow['jenis_kelamin']);
            }

            // Find or create Kelas
            $kelas = Kelas::firstOrCreate(
                ['nama_kelas' => trim($normalizedRow['kelas'])],
                ['nama_kelas' => trim($normalizedRow['kelas'])]
            );

            // Check if NIS already exists
            $existingSiswa = Siswa::where('nis', trim($normalizedRow['nis']))->first();
            if ($existingSiswa) {
                throw new \Exception("NIS {$normalizedRow['nis']} sudah terdaftar");
            }

            $siswa = new Siswa([
                'nama' => trim($normalizedRow['nama']),
                'nis' => trim($normalizedRow['nis']),
                'jenis_kelamin' => trim($normalizedRow['jenis_kelamin']),
                'kelas_id' => $kelas->id,
            ]);

            $this->successCount++;
            return $siswa;

        } catch (\Exception $e) {
            $this->errorCount++;
            $this->importErrors[] = [
                'row' => $this->rowNumber,
                'message' => $e->getMessage(),
                'values' => $row
            ];
            Log::error("Error importing siswa at row {$this->rowNumber}: " . $e->getMessage());
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
            'nama' => ['nama', 'name', 'nama_siswa', 'Nama', 'Nama Siswa'],
            'nis' => ['nis', 'nomor_induk', 'nomor_induk_siswa', 'NIS', 'Nomor Induk'],
            'jenis_kelamin' => ['jenis_kelamin', 'gender', 'jk', 'Jenis Kelamin', 'JK'],
            'kelas' => ['kelas', 'nama_kelas', 'class', 'Kelas', 'Nama Kelas']
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
            '*.nis' => 'required|numeric',
            '*.jenis_kelamin' => 'required|in:Laki-laki,Perempuan,L,P',
            '*.kelas' => 'required|string|max:50',
        ];
    }

    public function customValidationMessages()
    {
        return [
            '*.nama.required' => 'Kolom nama wajib diisi',
            '*.nama.regex' => 'Nama hanya boleh berisi huruf dan spasi',
            '*.nis.required' => 'Kolom NIS wajib diisi',
            '*.nis.numeric' => 'Kolom NIS harus berupa angka',
            '*.jenis_kelamin.required' => 'Kolom jenis kelamin wajib diisi',
            '*.jenis_kelamin.in' => 'Jenis kelamin harus Laki-laki, Perempuan, L, atau P',
            '*.kelas.required' => 'Kolom kelas wajib diisi',
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
