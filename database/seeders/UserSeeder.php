<?php

namespace Database\Seeders;

use App\Models\PersonalInfo;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'dirij75152@maxturns.com',
            'name' => 'Abc',
            'lName' => 'Def',
            'phone' => '+34123456123',
            'status' => 'active',
            'password' => 'f6b9ee3f-67cd-4f61-a7ef-c82b0de85660',
            'role_id' => 2
        ]);

        User::factory(10)->create(['role_id' => 1]);
    }
}
