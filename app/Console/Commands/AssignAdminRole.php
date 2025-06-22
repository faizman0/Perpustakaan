<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Role;

class AssignAdminRole extends Command
{
    protected $signature = 'user:make-admin {email}';
    protected $description = 'Assign admin role to a user';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("User with email {$email} not found!");
            return 1;
        }

        $adminRole = Role::where('slug', 'admin')->first();
        
        if (!$adminRole) {
            $this->error('Admin role not found! Please run the RolePermissionSeeder first.');
            return 1;
        }

        // Remove any existing roles
        $user->roles()->detach();
        
        // Assign admin role
        $user->roles()->attach($adminRole);
        
        $this->info("Admin role has been assigned to {$user->name} ({$user->email})");
        return 0;
    }
} 