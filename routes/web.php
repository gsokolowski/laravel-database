<?php

use App\Models\Address;
use App\Models\City;
use App\Models\Comment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {


    // one to one
    // user has address 
    $user = User::find(1); // user_id = 1
    $address = $user->address;

    // dump($address);
    
    // get user street and number
    $street = $user->address->street;
    $number = $user->address->number;
    
    // dump($street, $number);
    
    // address belongs to user
    $address = Address::find(2); // // address id = 1  
    // dump($address->user);


    // one to many
    // user has many comments
    $user =  User::find(1); // address_id = 1
    $comments = $user->comments;
    
    // dump($comments);

    // comment belongs to user
    // select * from `comments` where `comments`.`id` = 2 and `comments`.`deleted_at` is null limit 1
    $comment = Comment::find(3); // comment id = 2
    $user =  $comment->user;

    // dump($user);

    // many to many
    $city = City::find(1); // city id 1
    $rooms = $city->rooms; // rooms under the city
    // dump($rooms);


    $rooms = Room::where('size', 2)->get(); // i have rooms 
    dump($rooms);

    dump($rooms[0]->cities); // for room id 0 get me city

    // display city related to the room 
    foreach($rooms as $room) { 
        $city = $room->cities;
        dump($city);
    }
    
    // list just city names
    foreach ($rooms as $room) {
        foreach ($room->cities as $city) {
            echo $city->name.' ';
            echo 'room: ' ;
            echo $city->pivot->room_id.' '; 
        }
    }

    // too access pivot city_rooms table
    foreach ($rooms as $room) {
        foreach ($room->cities as $city) {
            echo $city->pivot->room_id.' '; 
        }
    }




    // dump($result);

    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Search completed successfully.',
    //     'data'   => $result,
    // ]);

    return view('app');
});
