<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Guru;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\GuruExports;
use App\Imports\GuruImport;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\GuruTemplateExport;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::all();
        return view('guru.index', 
        [
            'gurus' => $gurus,
            'key' => 'guru'
        ]);
    }

    public function create()
    {
        return view('guru.create', [
            'key' => 'guru'
        ]);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'nip' => 'required|max:20|unique:gurus',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        ], [
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi',
            'nip.regex' => 'NIP hanya boleh berisi angka',
            'nip.unique' => 'NIP sudah terdaftar'
        ]);

        Guru::create($request->all());
        return redirect(auth()->user()->hasRole('admin') ? '/admin/guru' : '/petugas/guru')->with('success', 'Guru berhasil ditambahkan.');     
    }

    public function edit(Guru $guru)
    {
        return view('guru.edit', 
        [
            'guru'=> $guru,
            'key'=> 'guru'
        ]);
    }

    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama' => 'required|string|max:255|regex:/^[\p{L}\s]+$/u',
            'nip' => 'required|max:20|unique:gurus,nip,' . $guru->id,
            'jenis_kelamin'=> 'required|in:Laki-laki,Perempuan',
        ], [
            'nama.regex' => 'Nama hanya boleh berisi huruf dan spasi',
            'nip.regex' => 'NIP hanya boleh berisi angka',
            'nip.unique' => 'NIP sudah terdaftar'
        ]);

        $guru->update($request->all());
        return redirect(auth()->user()->hasRole('admin') ? route('admin.guru.store') : route('petugas.guru.store'))->with('success', 'Guru berhasil diedit.');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();
        return redirect(auth()->user()->hasRole('admin') ? '/admin/guru' : '/petugas/guru')->with('success', 'Guru berhasil dihapus.');
    }


    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048' // Max 2MB
        ], [
            'file.required' => 'File Excel harus diunggah',
            'file.mimes' => 'File harus berformat Excel (.xlsx atau .xls)',
            'file.max' => 'Ukuran file maksimal 2MB'
        ]);

        try {
            $import = new GuruImport;
            Excel::import($import, $request->file('file'));

            $successCount = $import->getSuccessCount();
            $errorCount = $import->getErrorCount();
            $errors = $import->getErrors();

            $message = "Import selesai. Berhasil: {$successCount}, Gagal: {$errorCount}";
            
            if ($errorCount > 0) {
                $message .= "\nDetail error:";
                foreach ($errors as $error) {
                    $message .= "\nBaris {$error['row']}: ";
                    if (isset($error['errors'])) {
                        foreach ($error['errors'] as $field => $messages) {
                            $message .= "\n   - {$field}: " . implode(', ', $messages);
                        }
                    } else {
                        $message .= $error['message'];
                    }
                }
                return back()->with('warning', $message);
            }

            return back()->with('success', $message);
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();
            $message = "Validasi gagal:\n";
            foreach ($failures as $failure) {
                $message .= "Baris {$failure->row()}: ";
                foreach ($failure->errors() as $error) {
                    $message .= "\n   - " . $error;
                }
            }
            return back()->with('error', $message);
        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return back()->with('error', 'File Excel tidak valid atau rusak. Silakan periksa format file Anda.');
        } catch (\Exception $e) {
            if (str_contains($e->getMessage(), 'validateNumber')) {
                return back()->with('error', 'Format data tidak sesuai. Pastikan kolom NIP hanya berisi angka.');
            }
            return back()->with('error', 'Terjadi kesalahan saat import. Silakan periksa format data Anda dan coba lagi.');
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new GuruTemplateExport, 'template_import_guru.xlsx');
    }
}
