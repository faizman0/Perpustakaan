<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Exports\BukuExport;
use App\Imports\BukuImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class BukuController extends Controller
{
    public function index()
    {
        $bukus = Buku::with('kategori')->get();
        return view('buku.index', [
            'bukus' => $bukus,
            'key' => 'buku'
        ]);
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('buku.create', [
            'kategori' => $kategori,
            'key' => 'buku'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'no_inventaris' => 'required|string|unique:bukus',
            'no_klasifikasi' => 'required|string|unique:bukus',
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|integer|min:1900',
            'edisi' => 'nullable|string',
            'isbn' => 'nullable|string|unique:bukus',
            'kolase' => 'nullable|string',
            'jumlah' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        Buku::create($request->all());
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        $kategori = Kategori::all();
        return view('buku.edit', [
            'buku' => $buku,
            'kategori' => $kategori,
            'key' => 'buku'
        ]);
    }

    public function update(Request $request, Buku $buku)
    {
        $request->validate([
            'judul' => 'required',
            'no_inventaris' => 'required|string|unique:bukus,no_inventaris,' . $buku->id,
            'no_klasifikasi' => 'required|string|unique:bukus,no_klasifikasi,' . $buku->id,
            'pengarang' => 'required',
            'penerbit' => 'required',
            'tahun_terbit' => 'required|integer|min:1900',
            'edisi' => 'nullable|string',
            'isbn' => 'nullable|string|unique:bukus,isbn,' . $buku->id,
            'kolase' => 'nullable|string',
            'jumlah' => 'required|integer|min:0',
            'keterangan' => 'nullable|string',
            'kategori_id' => 'required|exists:kategoris,id',
        ]);

        $buku->update($request->all());
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil dihapus.');
    }

    /**
     * Export data buku ke Excel
     */
    public function exportExcel()
    {
        try {
            $filename = 'data_buku_' . date('Y-m-d_His') . '.xlsx';
            return Excel::download(new BukuExport, $filename);
        } catch (\Exception $e) {
            \Log::error('Export Excel failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    /**
     * Export data buku ke PDF
     */
    public function exportPDF()
    {
        try {
            $bukus = Buku::with('kategori')->get();
            $pdf = PDF::loadView('buku.pdf', compact('bukus'));
            $pdf->setPaper('a4', 'landscape');
            $filename = 'data_buku_' . date('Y-m-d_His') . '.pdf';
            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('Export PDF failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengexport data: ' . $e->getMessage());
        }
    }

    /**
     * Import data buku dari Excel
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:2048' // Max 2MB
        ]);

        try {
            \Log::info('Starting buku import');
            Excel::import(new BukuImport, $request->file('file'));
            \Log::info('Buku import successful');
            return back()->with('success', 'Data buku berhasil diimport!');
        } catch (\Exception $e) {
            \Log::error('Buku import failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengimport data: ' . $e->getMessage());  
        }
    }

    /**
     * Download template import buku
     */
    public function downloadTemplate()
    {
        try {
            return Excel::download(new \App\Exports\BukuTemplateExport, 'template_import_buku.xlsx');
        } catch (\Exception $e) {
            \Log::error('Template download failed: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh template: ' . $e->getMessage());
        }
    }
}