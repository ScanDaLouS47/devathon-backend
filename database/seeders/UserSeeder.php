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
        User::factory()->create(['role_id' => 2])->each(function ($user) {
            
            PersonalInfo::factory()->create(['user_id' => $user->id]);
        });
    

        User::factory(10)->create(['role_id' => 1])->each(function ($user) {
            
            PersonalInfo::factory()->create(['user_id' => $user->id]);
        });
    }
}
