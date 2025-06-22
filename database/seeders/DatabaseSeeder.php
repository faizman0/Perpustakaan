<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Buat user admin default
        User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Buat user petugas default
        User::create([
            'name' => 'Petugas',
            'username' => 'petugas',
            'email' => 'petugas@petugas.com',
            'password' => Hash::make('password'),
        ]);

        // Jalankan seeder role dan permission
        $this->call(RolePermissionSeeder::class);
        
        // Jalankan seeder untuk memberikan role ke user
        $this->call(UserRoleSeeder::class);
    }
}
