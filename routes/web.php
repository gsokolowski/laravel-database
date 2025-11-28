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


    // select * from `users` where `users`.`id` = 1 limit 1
    // select * from `comments` where 
    // (`comments`.`user_id` = 1 and `comments`.`user_id` is not null and `rating` > 3 or `rating` < 2) 
    // and `comments`.`deleted_at` is null    
    $result = User::find(1) // find user with id 1
                ->comments() // and return comments available in User model
                ->where('rating', '>', 3) // and query comments, where comment rating > 3
                ->orWhere('rating', '<', 2) // or < 2
                ->get(); // and get it at the end

    // select * from `users` where `users`.`id` = 1 limit 1
    // select * from `comments` where `comments`.`user_id` = 1 and `comments`.`user_id` is not null and 
    // (`rating` > 3 or `rating` < 2) 
    // and `comments`.`deleted_at` is null                
    $result = User::find(1)->comments()
                ->where(function($query){ // this adds a pair of parentises / branckets into query
                    return $query->where('rating', '>', 3)
                            ->orWhere('rating', '<', 2);
                })
                ->get();

    // select * from `users` where 
    // (select count(*) from `comments` 
    // where `users`.`id` = `comments`.`user_id` and `comments`.`deleted_at` is null) >= 6
    // give me user with at least 4 comments
    $result = User::has('comments', '>=', 4)->get();
    
    // select * from `comments` where exists 
    // (select * from `users` where `comments`.`user_id` = `users`.`id` 
    // and exists (select * from `addresses` where `users`.`id` = `addresses`.`user_id`)) 
    // and `comments`.`deleted_at` is null;
    // give me comments where user of that comment has address
    $result = Comment::has('user.address')->get();

    // select * from `users` where exists 
    // (select * from `comments` where `users`.`id` = `comments`.`user_id` and `rating` > 2 and `comments`.`deleted_at` is null)
    // give me user where it has comments with rating bigger then 2
    $result = User::whereHas('comments', function ($query) {
        $query->where('rating', '>', 2);
    })
    ->get();


    // select * from `users` where (select count(*) from `comments` where `users`.`id` = `comments`.`user_id` and `rating` > 1 and `comments`.`deleted_at` is null) >= 2
    // give me user where it has at least 2 comments with rating bigger then 1
    $result = User::whereHas('comments', function ($query) {
        $query->where('rating', '>', 1);
    }, '>=', 2)
    ->get();

    
    // select * from `users` where not exists 
    // (select * from `comments` where `users`.`id` = `comments`.`user_id` and `comments`.`deleted_at` is null)
    // Give me user with without comments
    $result = User::doesntHave('comments')->get(); // ->orDoesntHave

    // give me user who didnt write a comment with rating less then 2
    $result = User::whereDoesntHave('comments', function ($query) {
        $query->where('rating', '<', 2); // you can add where here
    })->get(); // ->orWhereDoesntHave

    // give me all reservations which dont have comments that are rated less then 2 stars
    $result = Reservation::whereDoesntHave('user.comments', function ($query) {
        $query->where('rating', '<', 2);
    })->get(); 

    // select `users`.*, 
    // (select count(*) from `comments` 
    // where `users`.`id` = `comments`.`user_id` and `comments`.`deleted_at` is null) as `comments_count` from `users`;
    // give me users with comments count
    $result = User::withCount('comments')->get();


    // select `users`.*, (select count(*) from `comments` where `users`.`id` = `comments`.`user_id` and `comments`.`deleted_at` is null) as `comments_count`, (select count(*) from `comments` where `users`.`id` = `comments`.`user_id` and `rating` <= 2 and `comments`.`deleted_at` is null) as `negative_comments_count` from `users`
    // give me all Users with  comments count with negative rating (rating less  than 3)
    $result = User::withCount([
        'comments',
        'comments as negative_comments_count' => function ($query) {
            $query->where('rating', '<', 3);
        },
    ])->get();
          
    // dump($result[0]->comments_count,$result[0]->negative_comments_count);


    dump($result);
    
    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Search completed successfully.',
    //     'data'   => $result,
    // ]);

    return view('app');
});
