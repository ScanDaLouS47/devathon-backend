<?php

namespace Database\Seeders;

use App\Models\Booking;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Booking::factory(50)->create();

        // for ($i = 0; $i < 50; $i++) {
        //     Booking::factory(fake()->numberBetween(0, 10))->create([fake()->dateTimeThisMonth('2024-08-31 00:00:00')]);
        // }
    }
}
