<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Image;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Attach an image to some users (e.g., first 10 users)
        User::limit(10)->get()->each(function ($user, $i) {
            // create via relation so imageable_{type,id} are set correctly
            $user->image()->create(Image::factory()->make()->toArray());
        });

        // Attach an image to some cities
        City::limit(3)->get()->each(function ($city) {
            $city->image()->create(Image::factory()->make()->toArray());
        });

        // Optionally: create a specific image for a particular user/city
        // $user = User::find(1);
        // $user->image()->create([
        //     'filename' => 'avatar.jpg',
        //     'path' => 'images/avatar_1.jpg',
        //     'mime_type' => 'image/jpeg'
        // ]);
    }
}
