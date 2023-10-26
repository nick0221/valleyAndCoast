<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SupplierProfile>
 */
class SupplierProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'suppName' => $this->faker->company,
            'contact' => $this->faker->e164PhoneNumber,
            'address' => $this->faker->address,
        ];
    }
}
