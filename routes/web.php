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

    $room = Room::find(1);
    // select * from `comments` where `comments`.`commentable_type` = 'App\\Models\\Room' and `comments`.`commentable_id` = 1 and `comments`.`commentable_id` is not null and `comments`.`deleted_at` is null
    $comments = $room->comments;

    dump($comments);

    $image = Image::find(5);
    // select * from `comments` where `comments`.`commentable_type` = 'App\\Models\\Image' and `comments`.`commentable_id` = 5 and `comments`.`commentable_id` is not null and `comments`.`deleted_at` is null
    $comments = $image->comments;
    dump($comments);

    $com = Comment::findOrFail(1);
    $whosCommentIsIt = $com->commentable; // shows what model is that commanet related to (Room or Image)
    dump($whosCommentIsIt);
 

    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Search completed successfully.',
    //     'data'   => $result,
    // ]);

    return view('app');
});
