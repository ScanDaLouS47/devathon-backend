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

        User::create([
            'email' => 'vohac64895@iteradev.com',
            'name' => 'Marcos',
            'lName' => 'El Guapeton',
            'phone' => '+34123456123',
            'status' => 'active',
            'password' => 'b4c1eca2-8d7e-48db-bb76-dd1e96107b0a',
            'role_id' => 1
        ]);

        User::create([
            'email' => 'xofohon521@kwalah.com',
            'name' => 'Marcos',
            'lName' => 'El Guapeton',
            'phone' => '+34123456456',
            'status' => 'active',
            'password' => 'a51f1cdb-ac0a-4b57-8d4c-6fec20361e8a',
            'role_id' => 1
        ]);

        User::factory(10)->create(['role_id' => 1]);
    }
}
