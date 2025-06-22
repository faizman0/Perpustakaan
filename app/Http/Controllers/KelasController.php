<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KelasController extends Controller
{
    // Menampilkan daftar kelas
    public function index()
    {
        $kelass = Kelas::with('siswa')->get();
        return view('kelas.index', 
        [
            'kelass' => $kelass,
            'key' => 'kelas'
        ]);
    }

    // Menampilkan form tambah kelas
    public function create()
    {
        return view('kelas.create', [
            'key' => 'kelas'
        ]);
    }

    // Menyimpan data kelas baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas'
        ]);

        Kelas::create($request->all());
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil ditambahkan');
    }

    // Menampilkan form edit kelas
    public function edit(Kelas $kelas)
    {
        return view('kelas.edit', [
            'kelas' => $kelas,
            'key' => 'kelas'
        ]);
    }

    // Memperbarui data kelas
    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'nama_kelas' => 'required|string|max:255|unique:kelas,nama_kelas,' . $kelas->id
        ]);

        $kelas->update($request->all());
        return redirect()->route('admin.kelas.index')->with('success', 'Kelas berhasil diperbarui');
    }

    // Menghapus data kelas
    public function destroy(Kelas $kelas)
    {
        try {
            DB::beginTransaction();
            
            // Hapus semua siswa yang terkait dengan kelas ini
            $kelas->siswa()->delete();
            
            // Hapus kelas
            $kelas->delete();
            
            DB::commit();
            return redirect()->route('admin.kelas.index')->with('success', 'Kelas dan data siswa terkait berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.kelas.index')->with('error', 'Gagal menghapus kelas: ' . $e->getMessage());
        }
    }

}
