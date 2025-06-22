<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        Permission::create($request->validate([
            'nama' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions',
            'deskripsi' => 'nullable|string'
        ]));

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil ditambahkan');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $permission->update($request->validate([
            'nama' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:permissions,slug,' . $permission->id,
            'deskripsi' => 'nullable|string'
        ]));

        return redirect()->route('permissions.index')->with('success', 'Permission berhasil diperbarui');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission berhasil dihapus');
    }
} 