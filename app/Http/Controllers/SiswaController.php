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
            'nama' => 'required|string|max:255',
            'nis' => 'required|unique:siswas',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas_id' => 'required|exists:kelas,id',
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
                return redirect()->route('admin.siswa.index')
                    ->with('error', 'Siswa tidak ditemukan.');
            }

            $kelas = Kelas::all();
            return view('siswa.edit', [
                'siswa' => $siswa,
                'kelas' => $kelas,
                'key' => 'siswa'
            ]);
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index')
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
                return redirect()->route('admin.siswa.index')
                    ->with('error', 'Siswa tidak ditemukan.');
            }

            $validated = $request->validate([
                'nama' => ['required', 'string', 'max:255', 'regex:/^[\p{L}\s]+$/u'],
                'nis' => [
                    'required',
                    'string',
                    'max:20',
                    'regex:/^[0-9]+$/',
                    'unique:siswas,nis,' . $siswa->id
                ],
                'jenis_kelamin' => ['required', 'in:Laki-laki,Perempuan'],
                'kelas_id' => ['required', 'exists:kelas,id'],
            ], [
                'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi',
                'nis.regex' => 'NIS hanya boleh berisi angka',
                'kelas_id.exists' => 'Kelas yang dipilih tidak valid'
            ]);

            // Sanitize input
            $validated['nama'] = trim($validated['nama']);
            $validated['nis'] = trim($validated['nis']);

            $siswa->update($validated);

            return redirect()->route('admin.siswa.index')
                ->with('success', 'Data siswa berhasil diperbarui.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('admin.siswa.index')
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
     * Export data member ke Excel
     */
    public function exportExcel()
    {
        return Excel::download(new SiswaExports, 'siswa.xlsx');
    }

    /**
     * Export data member ke PDF
     */
    public function exportPDF(): mixed
    {
        $siswa = Siswa::all();
        $pdf = PDF::loadView('siswa.pdf', compact('siswa'));
        return $pdf->download('siswa.pdf');
    }

    /**
     * Import data member dari Excel
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048' // Max 2MB
        ]);

        try {
            $import = new SiswaImport;
            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            $errors = $import->getErrors();

            $message = "Import selesai. Berhasil: {$successCount}, Gagal: {$errorCount}";
            
            if ($errorCount > 0) {
                $message .= "\nDetail error:";
                foreach ($errors as $error) {
                    $message .= "\nBaris {$error['row']}: {$error['message']}";
                }
                return back()->with('warning', $message);
            }

            return back()->with('success', $message);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $message = "Validasi gagal:\n";
            foreach ($failures as $failure) {
                $message .= "Baris {$failure->row()}: " . implode(', ', $failure->errors()) . "\n";
            }
            return back()->with('error', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat import: ' . $e->getMessage());
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