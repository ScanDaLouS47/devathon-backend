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
        User::factory()->create(['roleId' => 2])->each(function ($user) {
            
            PersonalInfo::factory()->create(['userId' => $user->id]);
        });
    

        User::factory(10)->create(['roleId' => 1])->each(function ($user) {
            
            PersonalInfo::factory()->create(['userId' => $user->id]);
        });
    }
}
