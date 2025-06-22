<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Buat atau update roles
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'nama' => 'Admin',
                'deskripsi' => 'Super user dengan semua akses'
            ]
        );

        $petugasRole = Role::firstOrCreate(
            ['slug' => 'petugas'],
            [
                'nama' => 'Petugas',
                'deskripsi' => 'Petugas dengan akses terbatas'
            ]
        );

        // Buat permissions
        $permissions = [
            // Permissions untuk Kategori
            ['nama' => 'Lihat Kategori', 'slug' => 'index-kategori'],
            ['nama' => 'Tambah Kategori', 'slug' => 'create-kategori'],
            ['nama' => 'Edit Kategori', 'slug' => 'edit-kategori'],
            ['nama' => 'Hapus Kategori', 'slug' => 'delete-kategori'],
            
            // Permissions untuk Buku
            ['nama' => 'Lihat Buku', 'slug' => 'index-buku'],
            ['nama' => 'Tambah Buku', 'slug' => 'create-buku'],
            ['nama' => 'Edit Buku', 'slug' => 'edit-buku'],
            ['nama' => 'Hapus Buku', 'slug' => 'delete-buku'],
            
            // Permissions untuk Kelas
            ['nama' => 'Lihat Kelas', 'slug' => 'index-kelas'],
            ['nama' => 'Tambah Kelas', 'slug' => 'create-kelas'],
            ['nama' => 'Edit Kelas', 'slug' => 'edit-kelas'],
            ['nama' => 'Hapus Kelas', 'slug' => 'delete-kelas'],
            
            // Permissions untuk Siswa
            ['nama' => 'Lihat Siswa', 'slug' => 'index-siswa'],
            ['nama' => 'Tambah Siswa', 'slug' => 'create-siswa'],
            ['nama' => 'Edit Siswa', 'slug' => 'edit-siswa'],
            ['nama' => 'Hapus Siswa', 'slug' => 'delete-siswa'],
            
            // Permissions untuk Guru
            ['nama' => 'Lihat Guru', 'slug' => 'index-guru'],
            ['nama' => 'Tambah Guru', 'slug' => 'create-guru'],
            ['nama' => 'Edit Guru', 'slug' => 'edit-guru'],
            ['nama' => 'Hapus Guru', 'slug' => 'delete-guru'],
            
            // Permissions untuk Anggota
            ['nama' => 'Lihat Anggota', 'slug' => 'index-anggota'],
            ['nama' => 'Tambah Anggota', 'slug' => 'create-anggota'],
            ['nama' => 'Edit Anggota', 'slug' => 'edit-anggota'],
            ['nama' => 'Hapus Anggota', 'slug' => 'delete-anggota'],
            
            // Permissions untuk Kunjungan
            ['nama' => 'Lihat Kunjungan', 'slug' => 'index-kunjungan'],
            ['nama' => 'Tambah Kunjungan', 'slug' => 'create-kunjungan'],
            ['nama' => 'Edit Kunjungan', 'slug' => 'edit-kunjungan'],
            ['nama' => 'Hapus Kunjungan', 'slug' => 'delete-kunjungan'],
            
            // Permissions untuk Peminjaman
            ['nama' => 'Lihat Peminjaman', 'slug' => 'index-peminjaman'],
            ['nama' => 'Tambah Peminjaman', 'slug' => 'create-peminjaman'],
            ['nama' => 'Edit Peminjaman', 'slug' => 'edit-peminjaman'],
            ['nama' => 'Hapus Peminjaman', 'slug' => 'delete-peminjaman'],
            
            // Permissions untuk Pengembalian
            ['nama' => 'Lihat Pengembalian', 'slug' => 'index-pengembalian'],
            ['nama' => 'Tambah Pengembalian', 'slug' => 'create-pengembalian'],
            ['nama' => 'Edit Pengembalian', 'slug' => 'edit-pengembalian'],
            ['nama' => 'Hapus Pengembalian', 'slug' => 'delete-pengembalian'],
            
            // Permissions untuk Sistem
            ['nama' => 'Kelola Pengguna', 'slug' => 'manage-users'],
            ['nama' => 'Kelola Role', 'slug' => 'manage-roles'],
            ['nama' => 'Kelola Permission', 'slug' => 'manage-permissions'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['slug' => $permission['slug']],
                ['nama' => $permission['nama']]
            );
        }

        // Berikan semua permission ke role admin
        $adminRole->permissions()->sync(Permission::all());

        // Berikan permission terbatas ke role petugas
        $petugasRole->permissions()->sync(
            Permission::whereIn('slug', [
                // Kunjungan (CR)
                'index-kunjungan', 'create-kunjungan',
                
                // Peminjaman (CR)
                'index-peminjaman', 'create-peminjaman',
                
                // Pengembalian (CR)
                'index-pengembalian', 'create-pengembalian',

                // Guru (CR)
                'index-guru', 'create-guru',

                // Siswa (CR)
                'index-siswa', 'create-siswa',

                // Anggota (CR)
                'index-anggota', 'create-anggota',
            ])->get()
        );

        // Berikan role admin ke user admin default
        $admin = User::where('email', 'admin@admin.com')->first();
        if ($admin) {
            $admin->roles()->sync([$adminRole->id]);
        }
    }
} 