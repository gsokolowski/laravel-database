<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $filename = $this->faker->lexify('image_????.jpg');
        $path = 'images/' . Str::random(24) . '.jpg';

        return [
            'filename' => $filename,
            'path' => $path,
            'mime_type' => 'image/jpeg',
            'alt' => $this->faker->sentence(3),
            'size' => $this->faker->numberBetween(5000, 2000000),
        ];
    }
}
