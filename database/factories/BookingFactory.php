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
            'reservationDate' => fake()->date(),
            'userId' => User::factory(),
            'total_capacity' => fake()->numberBetween(2,8),
            'persons' => fake()->randomNumber(),
            'shift_id' => Shift::factory(),
            'adicional_info' => fake()->text(),
            'allergens' => fake()->boolean(),
            'statusId' => 1,
            'number' => fake()->randomNumber()            
        ];
    }
}
