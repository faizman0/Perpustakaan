<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class AssignAdminRoleSeeder extends Seeder
{
    public function run(): void
    {
        // Get admin role
        $adminRole = Role::where('slug', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('Admin role not found. Please run the RolePermissionSeeder first.');
            return;
        }

        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // If user has no roles, assign admin role
            if ($user->roles()->count() === 0) {
                $user->roles()->attach($adminRole);
                $this->command->info("Assigned admin role to user: {$user->name}");
            }
        }

        $this->command->info('Admin role assignment completed.');
    }
} 