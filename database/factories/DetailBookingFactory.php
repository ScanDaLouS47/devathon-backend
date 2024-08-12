<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\DetailBooking;
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

    protected $model = DetailBooking::class;

    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'table_id' => Table::factory()
        ];
    }
}
