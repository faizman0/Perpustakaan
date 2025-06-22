<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Exports\SiswaExports;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Kelas;
use App\Exports\SiswaTemplateExport;


class SiswaController extends Controller
{
    /**
     * Menampilkan daftar member.
     */
    public function index()
    {
        $siswas = Siswa::all();
        return view('siswa.index', [
            'siswas' => $siswas,
            'key' => 'siswa'
        ]);
    }

    /**
     * Menampilkan form untuk menambahkan member baru.
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('siswa.create', [
            'kelas' => $kelas,
            'key' => 'siswa'
        ]);
    }

    /**
     * Menyimpan member baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'nis' => 'required|max:20|unique:siswas',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas_id' => 'required|exists:kelas,id',
        ], [
            'nama.required' => 'Nama siswa wajib diisi',
            'nama.string' => 'Nama siswa harus berupa teks',
            'nama.max' => 'Nama siswa maksimal 255 karakter',
            'nama.regex' => 'Nama siswa hanya boleh berisi huruf dan spasi',
            'nis.required' => 'NIS siswa wajib diisi',
            'nis.max' => 'NIS siswa maksimal 20 karakter',
            'nis.unique' => 'NIS siswa sudah terdaftar dalam sistem',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus dipilih antara Laki-laki atau Perempuan',
            'kelas_id.required' => 'Kelas wajib dipilih',
            'kelas_id.exists' => 'Kelas yang dipilih tidak valid'
        ]);

        Siswa::create($request->all());
        return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.siswa.index' : 'petugas.siswa.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit member.  
     */
    public function edit(Siswa $siswa)
    {
        try {
            if (!$siswa) {
                return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.siswa.index' : 'petugas.siswa.index')
                    ->with('error', 'Siswa tidak ditemukan.');
            }

            $kelas = Kelas::all();
            return view('siswa.edit', [
                'siswa' => $siswa,
                'kelas' => $kelas,
                'key' => 'siswa'
            ]);
        } catch (\Exception $e) {
            return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.siswa.index' : 'petugas.siswa.index')
                ->with('error', 'Terjadi kesalahan saat mengedit siswa: ' . $e->getMessage());
        }
    }

    /**
     * Mengupdate data member di database.
     */
    public function update(Request $request, Siswa $siswa): RedirectResponse
    {
        try {
            if (!$siswa) {
                return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.siswa.index' : 'petugas.siswa.index')
                    ->with('error', 'Siswa tidak ditemukan.');
            }

            $validated = $request->validate([
                'nama' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
                'nis' => [
                    'required',
                    'max:20',
                    'unique:siswas,nis,' . $siswa->id
                ],
                'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                'kelas_id' => ['required', 'exists:kelas,id'],
            ], [
                'nama.required' => 'Nama siswa wajib diisi',
                'nama.string' => 'Nama siswa harus berupa teks',
                'nama.max' => 'Nama siswa maksimal 255 karakter',
                'nama.regex' => 'Nama siswa hanya boleh berisi huruf dan spasi',
                'nis.required' => 'NIS siswa wajib diisi',
                'nis.max' => 'NIS siswa maksimal 20 karakter',
                'nis.unique' => 'NIS siswa sudah terdaftar dalam sistem',
                'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
                'jenis_kelamin.in' => 'Jenis kelamin harus dipilih antara Laki-laki atau Perempuan',
                'kelas_id.required' => 'Kelas wajib dipilih',
                'kelas_id.exists' => 'Kelas yang dipilih tidak valid'
            ]);

            // Sanitize input
            $validated['nama'] = trim($validated['nama']);
            $validated['nis'] = trim($validated['nis']);

            $siswa->update($validated);

            return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.siswa.index' : 'petugas.siswa.index')
                ->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route(auth()->user()->hasRole('admin') ? 'admin.siswa.index' : 'petugas.siswa.index')
                ->with('error', 'Terjadi kesalahan saat memperbarui data siswa: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus member dari database.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('admin.siswa.index')->with('success', 'Siswa berhasil dihapus.');
    }

    /**
     * Import data dari Excel
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048' // Max 2MB
        ], [
            'file.required' => 'File Excel wajib diunggah',
            'file.mimes' => 'File harus berformat Excel (.xlsx atau .xls)',
            'file.max' => 'Ukuran file maksimal 2MB'
        ]);

        try {
            $import = new SiswaImport;
            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            $errors = $import->getErrors();

            if ($errorCount > 0) {
                $errorMessage = "Import selesai dengan beberapa kesalahan:\n";
                $errorMessage .= "✓ Berhasil diimpor: {$successCount} data\n";
                $errorMessage .= "✗ Gagal diimpor: {$errorCount} data\n";
                $errorMessage .= "Detail kesalahan:\n";
                
                foreach ($errors as $error) {
                    $errorMessage .= "Baris {$error['row']}: {$error['message']}\n";
                    if (isset($error['values'])) {
                        $errorMessage .= "Data: " . implode(', ', $error['values']) . "\n";
                    }
                }
                
                return back()->with('warning', $errorMessage);
            }

            return back()->with('success', "Import berhasil! {$successCount} data siswa berhasil diimpor.");
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $errorMessage = "Validasi data gagal:\n";
            
            foreach ($failures as $failure) {
                $errorMessage .= "Baris {$failure->row()}: " . implode(', ', $failure->errors()) . "\n";
                $errorMessage .= "Data: " . implode(', ', $failure->values()) . "\n";
            }
            
            return back()->with('error', $errorMessage);
        } catch (\Exception $e) {
            $errorMessage = "Terjadi kesalahan saat mengimpor data:\n";
            $errorMessage .= $e->getMessage() . "\n";
            $errorMessage .= "Pastikan format file Excel sesuai dengan template yang disediakan.";
            
            return back()->with('error', $errorMessage);
        }
    }

    /**
     * Download template import siswa
     */
    public function downloadTemplate()
    {
        return Excel::download(new SiswaTemplateExport, 'template_import_siswa.xlsx');
    }
}