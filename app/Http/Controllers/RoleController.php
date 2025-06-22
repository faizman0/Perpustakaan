<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $role = Role::create($request->validate([
            'nama' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles',
            'deskripsi' => 'nullable|string'
        ]));

        if ($request->has('permissions')) {
            $role->permissions()->attach($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role berhasil ditambahkan');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->validate([
            'nama' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:roles,slug,' . $role->id,
            'deskripsi' => 'nullable|string'
        ]));

        if ($request->has('permissions')) {
            $role->permissions()->sync($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Role berhasil diperbarui');
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role berhasil dihapus');
    }
} 