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

    $user = User::find(1);
    // select * from `images` where `images`.`imageable_type` = 'App\\Models\\User' and `images`.`imageable_id` = 1 and `images`.`imageable_id` is not null limit 1
    $image = $user->image; // morphOne = one related model returns as an object (single model), NOT a collection only morphMany = many related models (collection)
    
    //Returns the relation builder, NOT the image and does not send sql to DB - never use that 
    // $image = $user->image(); 

    $filename = $user->image?->filename;
    $path     = $user->image?->path;
    $alt      = $user->image?->alt;
    $url = $user->image?->url();
    
    // dump($image);

    $city = City::find(2);
    $image = $city->image;
    
    // dump($image);

    $image = Image::find(11);
    $whosImageIsIt = $image->imageable;

    dump($whosImageIsIt);


    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Search completed successfully.',
    //     'data'   => $result,
    // ]);

    return view('app');
});
