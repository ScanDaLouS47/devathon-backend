<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\DetailBooking;
use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailBookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailBooking::factory(10)->create()->each(function ($booking){
            Booking::factory(3)->create(['id_booking' => $booking->id]);
        })->each(function ($table) {
            Table::factory(3)->create(['id_table' => $table->id]);
        });
    }
}
