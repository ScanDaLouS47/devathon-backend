<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Table;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailBooking>
 */
class DetailBookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_booking' => Booking::factory(),
            'id_table' => Table::factory()
        ];
    }
}
