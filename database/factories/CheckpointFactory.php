<?php

namespace Database\Factories;

use App\Models\Checkpoint;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Checkpoint>
 */
class CheckpointFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Checkpoint ' . fake()->unique()->numberBetween(1, 1000000),

            // Координати приблизно в межах Він.обл.
            'latitude' => fake()->randomFloat(7, 49.85, 48.08),
            'longitude' => fake()->randomFloat(7, 27.38, 29.98),
        ];
    }
}
