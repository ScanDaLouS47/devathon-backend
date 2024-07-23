<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PersonalInfo>
 */
class PersonalInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'name' => fake()->name(),
        'lName' => fake()->lastName(),
        'address' => fake()->address(),
        'phone' => fake()->phoneNumber(),
        'dni' => fake()->phoneNumber(),
        'gender' => 'female',
        'age' => random_int(18, 99),
        'birthDate' => fake()->date(),
        ];
    }
}
