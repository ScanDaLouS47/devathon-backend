<?php

namespace Database\Factories;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shifts>
 */
class ShiftFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = Shift::class;
    // para el push

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'started_at' => fake()->time(),
            'finish_at' => fake()->time()
        ];
    }
}
