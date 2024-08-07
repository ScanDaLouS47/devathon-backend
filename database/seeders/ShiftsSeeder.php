<?php

namespace Database\Seeders;

use App\Models\Shift;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultValues = [
            ['name' => 'Turno 1', 'started_at' => '12:30:00', 'finish_at' => '14:30:00'],
            ['name' => 'Turno 2', 'started_at' => '14:30:00', 'finish_at' => '16:30:00'],
            ['name' => 'Turno 3', 'started_at' => '19:30:00', 'finish_at' => '21:30:00'],
            ['name' => 'Turno 4', 'started_at' => '21:30:00', 'finish_at' => '23:30:00']
        ];

        foreach($defaultValues as $value) {
            Shift::create($value);
        }
    }
}
