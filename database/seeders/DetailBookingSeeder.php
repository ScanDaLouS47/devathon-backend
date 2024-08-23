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

        Booking::all()->each(function ($booking) {
            DetailBooking::factory()->create([
                'booking_id' => $booking->id,
                'table_id' => fake()->numberBetween(0, 25)
            ]);
            DetailBooking::factory()->create([
                'booking_id' => $booking->id,
                'table_id' => fake()->numberBetween(0, 25)
            ]);
        });



        // for ($i = 0; $i < 10; $i++) {
        //     $table = Table::factory()->create();

        //     DetailBooking::factory()->create([
        //         'booking_id' => $booking->id,
        //         'table_id' => $table->id,
        //     ]);
        // }

        // DetailBooking::factory()->has(Booking::factory()->count(3), 'bookings')->create();
        // DetailBooking::factory(10)->create()->each(function ($booking){
        //     Booking::factory(3)->create(['id_booking' => $booking->id]);
        // })->each(function ($table) {
        //     Table::factory(3)->create(['id_table' => $table->id]);
        // });
    }
}
