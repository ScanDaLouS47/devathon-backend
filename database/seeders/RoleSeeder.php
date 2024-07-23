<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    
    public function run(): void
    {
        Role::create([
            'type' => 'user',
            'permissions' => 'Manage your own account and reservations'
        ]);

        Role::create([
            'type' => 'admin',
            'permissions' => 'Manage all'
        ]);

        Role::create([
            'type' => 'guest',
            'permissions' => 'Create account and view pages available without authorization'
        ]);
    }
}
