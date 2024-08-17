<?php

namespace Database\Factories;

use App\Models\Shift;
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
            'reservationDate' => fake()->dateTimeThisMonth(),
            'user_id' => User::factory(),
            'persons' => fake()->numberBetween(0, 10),
            'shift_id' => fake()->numberBetween(1, 4),
            'additional_info' => fake()->text(),
            'allergens' => fake()->boolean(),
            'statusId' => 1,
        ];
    }
}
