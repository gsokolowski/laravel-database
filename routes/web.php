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

    $user = User::find(4);
    dump($user->likedRooms, $user->likedImages);

    die();

    // Get users who liked a room
    $room = Room::with('likers')->find(1);
    $likers = $room->likers; // Collection of User models

    dump($likers);

    // Get rooms a user has liked
    $user = User::with('likedRooms')->find(1);
    $likedRooms = $user->likedRooms; // Collection of Room models


    // count users who liked a model
    $likesCount = $room->likers()->count();
    dump($likesCount);

    // count how many rooms a user liked
    $roomsLikedCount = $user->likedRooms()->count();
    dump($roomsLikedCount);

    //Check whether a user liked a specific item
    // from the model side:
    $isLiked = $room->likers()->where('users.id', $user->id)->exists();
    dump($isLiked);
    
    // from the user side:
    $isLiked2 = $user->likedRooms()->where('rooms.id', $room->id)->exists();
    dump($isLiked2);

    // user likes a room
    // $user->likedRooms()->attach($room->id); // creates pivot entry

    // user unlikes a room
    // $user->likedRooms()->detach($room->id);

    // toggle (attach if missing, detach if present)
    // $user->likedRooms()->toggle($room->id);
    
    // Eager loading to avoid N+1
    // When listing rooms with their like counts and whether current user liked them:

    $user = User::with('likedRooms')->find(1);
    
    $rooms = Room::withCount('likers')->get();

    $currentUserLikedRoomIds = [];
    
    if ($user) {
        $currentUserLikedRoomIds = $user->likedRooms()->pluck('rooms.id')->toArray();
    }
    
    foreach ($rooms as $room) {
        $room->is_liked_by_current_user = in_array($room->id, $currentUserLikedRoomIds);
    }

    
    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Search completed successfully.',
    //     'data'   => $result,
    // ]);

    return view('app');
});
