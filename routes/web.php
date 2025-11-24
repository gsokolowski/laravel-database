<?php

use App\Models\Comment;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {


    $result = User::select([
        'users.*',
        'last_commented_at' => Comment::selectRaw('MAX(created_at)')
            ->whereColumn('user_id', 'users.id')
    ])->withCasts([
        'last_commented_at' => 'datetime:Y-m-d' // date and datetime works only for array or json result
    ])->get()->toJson();

    dump($result);

    // dump($result);

    // return response()->json([
    //     'status' => 200,
    //     'message' => 'Search completed successfully.',
    //     'data'   => $result,
    // ]);

    return view('app');
});
