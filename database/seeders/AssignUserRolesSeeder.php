<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class AssignUserRolesSeeder extends Seeder
{
    public function run(): void
    {
        // Get roles
        $adminRole = Role::where('slug', 'admin')->first();
        $petugasRole = Role::where('slug', 'petugas')->first();

        if (!$adminRole || !$petugasRole) {
            $this->command->error('Roles not found. Please run the RolePermissionSeeder first.');
            return;
        }

        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // If user has no roles, assign petugas role
            if ($user->roles()->count() === 0) {
                $user->roles()->attach($petugasRole);
                $this->command->info("Assigned petugas role to user: {$user->name}");
            }
        }

        $this->command->info('Role assignment completed.');
    }
} 