<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class CommentFactory extends Factory
{    
    public function definition(): array
    {
        return [
            'comment' => fake()->sentence(),
            'user_id' => fake()->numberBetween(1, 5),
            'rating' => fake()->numberBetween(1, 5),
        ];
    }
}
