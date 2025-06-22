<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil role petugas
        $petugasRole = Role::where('slug', 'petugas')->first();

        if ($petugasRole) {
            // Ambil semua user yang belum memiliki role
            $users = User::whereDoesntHave('roles')->get();

            // Berikan role petugas ke semua user yang belum memiliki role
            foreach ($users as $user) {
                $user->roles()->attach($petugasRole);
            }
        }
    }
} 