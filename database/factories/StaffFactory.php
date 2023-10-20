<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Staff>
 */
class StaffFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'firstname' => $this->faker->firstName,
            'lastname' => $this->faker->lastName,
            'gender' => $this->faker->randomElement(['Male', 'Female']),
            'contact' => $this->faker->e164PhoneNumber(),
            'address' => $this->faker->address,
            'designation_id' => $this->faker->numberBetween(1, 30),
            'dateHired' => $this->faker->dateTimeBetween('-10 years', '-5 months')

        ];
    }
}
