<?php

use App\Models\Address;
use App\Models\City;
use App\Models\Comment;
use App\Models\Company;
use App\Models\Image;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {


    // select * from `comments` 
    // where ((`comments`.`commentable_type` = 'App\\Models\\Image' and exists 
    // (select * from `images` where `comments`.`commentable_id` = `images`.`id`)) 
    // or (`comments`.`commentable_type` = 'App\\Models\\Room' and exists (select * from `rooms` 
    // where `comments`.`commentable_id` = `rooms`.`id`))) and `comments`.`deleted_at` is null
    // Give me all comments with commentable type Image and Room
    $result = Comment::whereHasMorph(
        'commentable',
        ['App\Models\Image', 'App\Models\Room'],
    )->get();


    // select * from `comments` where 
    // ((`comments`.`commentable_type` = 'App\\Models\\Image' and exists (select * from `images` where `comments`.`commentable_id` = `images`.`id`)) or (`comments`.`commentable_type` = 'App\\Models\\Room' and exists (select * from `rooms` where `comments`.`commentable_id` = `rooms`.`id`))) and `comments`.`deleted_at` is null
    $result = Comment::whereHasMorph(
        'commentable',
        ['App\Models\Image', 'App\Models\Room'],
        function ($query, $type) {
            if ($type === 'App\Room')
            {
                $query->where('room_size', '>', 2);
                $query->orWhere('room_size', '<', 2);
            }
            if ($type === 'App\Image')
            {
                $query->where('path', 'like', '%lorem%');
            }        
        }
    )->get();

    // select * from `comments` where `commentable_type` = 'App\\Models\\Room' and `rating` >= 3 and `comments`.`deleted_at` is null
    // Get all Comments of Room where comment rating is at least 3
    $result = Comment::where('commentable_type', Room::class)
        ->where('rating', '>=', 3)
        ->get();


    // Get all Comments of Room where comment rating is at least 3
    // select * from `comments` where `commentable_type` = 'App\\Models\\Image' and `rating` >= 3 and `comments`.`deleted_at` is null
    $result = Comment::where('commentable_type', Image::class)
        ->where('rating', '>=', 3)
        ->get();        
    
    // Get commentable of Image for comment id = 1
    //select * from `comments` where `commentable_type` = 'App\\Models\\Image' and `comments`.`id` = 1 and `comments`.`deleted_at` is null limit 1
    // $result = Comment::where('commentable_type', Image::class)->find(1);
    
    // Comments with comentable of Image for all Comments Alternative: Using whereHasMorph (ensures the related Image exists)
    // select * from `comments` where ((`comments`.`commentable_type` = 'App\\Models\\Image' and exists (select * from `images` where `comments`.`commentable_id` = `images`.`id`))) and `comments`.`deleted_at` is null
    $result = Comment::whereHasMorph('commentable', Image::class)->get();
          
        
dump($result);



    
    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Search completed successfully.',
    //     'data'   => $result,
    // ]);

    return view('app');
});
