<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Room;
use App\Models\Image;

class LikeableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $rooms = Room::all();
        $images = Image::all();

        if ($users->isEmpty()) {
            $this->command->warn('No users found — seeder skipping likes');
            return;
        }

        // For each user, randomly like some rooms and images
        foreach ($users as $user) {
            // randomly pick up to 5 rooms to like
            $roomsToLike = $rooms->random(rand(0, min(5, $rooms->count())))->pluck('id')->toArray();
            if (!empty($roomsToLike)) {
                // attach will create entries in likeables; withTimestamps() on relation fills timestamps
                $user->likedRooms()->attach($roomsToLike);
            }

            // randomly pick up to 6 images to like
            $imagesToLike = $images->random(rand(0, min(6, $images->count())))->pluck('id')->toArray();
            if (!empty($imagesToLike)) {
                $user->likedImages()->attach($imagesToLike);
            }
        }
    }
}
