<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'user_id' => fake()->numberBetween(1, 3),
            // 'room_id' => fake()->numberBetween(1, 10),
            // 'city_id' => fake()->numberBetween(1, 3),
            
            // this is better solution Instead of random numbers, it always picks an existing real ID from the database:
            'user_id' => User::inRandomOrder()->value('id'),
            'room_id' => Room::inRandomOrder()->value('id'),
            'city_id' => City::inRandomOrder()->value('id'),
            'check_in' => fake()->dateTimeBetween('-10 days','now'),
            'check_out' => fake()->dateTimeBetween('now', '+10 days'),
        ];
    }
}
