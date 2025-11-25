<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\City;
use Illuminate\Database\Seeder;

class CityRoomSeeder extends Seeder
{
    public function run()
    {
        $rooms = Room::all();
        $cities = City::all();

        foreach ($rooms as $room) {
            // attach 1–3 random cities to each room
            $room->cities()->attach(
                $cities->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
