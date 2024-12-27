<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Listing>
 */
class ListingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category' => fake()->randomElement(['SUV', 'Sedan', 'Truck', 'Coupe', 'Hatchback']),
            'make' => fake()->randomElement(['Toyota', 'Honda', 'Ford', 'Chevrolet', 'BMW', 'Mercedes', 'Nissan']),
            'model' => fake()->word(),
            'year' => fake()->numberBetween(2000, 2024),
            'engine_type' => fake()->randomElement(['Gasoline', 'Diesel', 'Electric', 'Hybrid']),
            'horsepower' => fake()->numberBetween(75, 800),
            'total_kilometers' => fake()->numberBetween(0, 300000),
            'color' => fake()->safeColorName(),
            'city' => fake()->city(),
            'price' => fake()->numberBetween(5000, 100000)
        ];
    }
}
