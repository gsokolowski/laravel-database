<?php

use App\Models\Comment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    // select * from `users` order by `name` desc
    $result = DB::table('users')
                ->orderBy('name', 'desc')
                ->get();

    // select `name` as `somethig`, `email` from `users` order by `created_at` desc limit 1
    $result = DB::table('users')
                ->select('name as somethig','email')
                ->latest() // created_at default
                ->first();
                
    // select * from `users` order by RAND(), RAND()
    $result = DB::table('users')
                ->inRandomOrder()
                ->orderByRaw('RAND()')
                ->get();


    // select count(id) as number_of_5stars_comments, rating from comments group by `rating` having rating = 5;
    $result = DB::table('comments')
                ->selectRaw('count(id) as number_of_5stars_comments, rating')
                ->groupBy('rating')
                ->having('rating', '=', 5)
                ->get();

    // select * from `comments` limit 3 offset 5
    $result = DB::table('comments')
                ->skip(5)
                ->take(3)
                ->get();

    // select * from `comments` limit 3 offset 5            
    $result = DB::table('comments')
                ->offset(5)
                ->limit(3)
                ->get();

    return response()->json([
        'status' => 200,
        'message' => 'Search completed successfully.',
        'data'   => $result,
    ]);


    dump($result);
    return view('app');
});
