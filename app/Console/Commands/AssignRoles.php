<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class AssignRoles extends Command
{
    protected $signature = 'roles:assign';
    protected $description = 'Assign roles to users';

    public function handle()
    {
        // Get all users
        $users = User::all();
        
        // Get roles
        $adminRole = Role::where('slug', 'admin')->first();
        $petugasRole = Role::where('slug', 'petugas')->first();

        if (!$adminRole || !$petugasRole) {
            $this->error('Roles not found. Please run the RolePermissionSeeder first.');
            return;
        }

        foreach ($users as $user) {
            // If user has no roles, assign petugas role
            if ($user->roles()->count() === 0) {
                $user->roles()->attach($petugasRole);
                $this->info("Assigned petugas role to user: {$user->name}");
            }
        }

        $this->info('Role assignment completed.');
    }
} 