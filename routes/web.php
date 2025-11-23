<?php

use App\Models\Comment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {


    // $result = Comment::all()->toArray();
    // $result = Comment::all()->count();
    // $result = Comment::all()->toJson()
    // $result = Comment::all()->toQuery();

    
    $comments = Comment::all();
    // $comments = Comment::rating(3)->get();

    // reject on collection
    $result = $comments->reject(function ($comment) {
        return $comment->rating < 3; // reject comments with rating less then 3
    });

    // gives you difrence between results above and all results so gives you results of rating > 3
    $result = $comments->diff($result);

    // map on collection and do what you want on it 
    $result = $comments->map(function ($comment) {
        return $comment->comment;
    });
    
    dump($result);

    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Search completed successfully.',
    //     'data'   => $result,
    // ]);

    return view('app');
});
