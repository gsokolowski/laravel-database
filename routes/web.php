<?php

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {

    // adding fulltext_index for makeing comment field indexed FULLTEXT KEY `fulltext_index` (`comment`)
    //$result = DB::statement('ALTER TABLE comments ADD FULLTEXT fulltext_index(comment)'); // MySQL >= 5.6

    // select * from `comments` where MATCH(comment) AGAINST('doloremque' IN BOOLEAN MODE)
    // select * from `comments` where MATCH(comment) AGAINST('+doloremque, -iure' IN BOOLEAN MODE) // contains doloremque and does not contain iure
    $result = DB::table('comments')
                ->whereRaw("MATCH(comment) AGAINST(? IN BOOLEAN MODE)", ['+doloremque, -iure'])
                ->get();

    // like is much slower in comparisom to fulltext search
    // $result = DB::table('comments')
    // ->where("content", 'like', '%doloremque%')
    // ->get();

    dump($result);
    
    return view('app');
});
