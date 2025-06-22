<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori.index', [
            'kategoris' => $kategoris,
            'key' => 'kategori'
        ]);
    }

    public function create()
    {
        return view('kategori.create', [
            'key' => 'kategori'
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|unique:kategoris',
        ], [
            'nama.required' => 'Nama kategori wajib diisi',
            'nama.unique' => 'Nama kategori sudah ada'
        ]);

        Kategori::create($request->all());
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        return view('kategori.edit', [
            'kategori' => $kategori,
            'key' => 'kategori'
        ]);
    }

    public function update(Request $request, Kategori $kategori)
    {
        $request->validate([
            'nama' => 'required|unique:kategoris,nama,' . $kategori->id,
        ], [
            'nama.required' => 'Nama kategori wajib diisi',
            'nama.unique' => 'Nama kategori sudah ada'
        ]);

        $kategori->update($request->all());
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diedit.');
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();
        return redirect()->route('admin.kategori.index')->with('success','Kategori berhasil dihapus');
    }
}