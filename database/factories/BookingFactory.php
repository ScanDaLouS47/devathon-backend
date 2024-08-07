<?php

namespace Database\Factories;

use App\Models\Table;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reservationDate' => fake()->date(),
            'userId' => User::factory(),
            'tableId' => Table::factory(),
            'statusId' => 1,
            'number' => fake()->randomNumber()
        ];
    }
}
