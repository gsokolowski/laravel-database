<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CompanySeeder::class,
            UserSeeder::class,
            CommentSeeder::class,
            CitySeeder::class,
            RoomSeeder::class,
            ReservationSeeder::class,
            AddressSeeder::class,
            CityRoomSeeder::class,
            ImageSeeder::class, // call it last
            LikeableSeeder::class,
        ]
        );
    }
}
