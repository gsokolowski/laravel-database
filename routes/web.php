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

    

    // toJson()
    return $result = User::with('comments')->get()->makeVisible('password')->toJson(); // makeHidden()
    // toArray()
    //$result = User::with('comments')->get()->toArray();

    
    //return $result;

    //dump($result);

    
    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Search completed successfully.',
    //     'data'   => $result,
    // ]);

    return view('app');
});
