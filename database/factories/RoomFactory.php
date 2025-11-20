<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'number' => fake()->numberBetween(1,10), //single or douple bed
            'size' => fake()->numberBetween(1,2), //single or douple bed
            'price' => fake()->numberBetween(100,200), //single or douple bed
            'description' => fake()->text(1000)
        ];
    }
}
