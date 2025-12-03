<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CityRoom extends Pivot
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'city_id',
        'room_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted()
    {
        // whenever we save new relationship betwin City and Room and save in DB city_room table this code on created will be executed
        // we are listeing to created event
        static::created(function ($cityroom) {
            dump($cityroom, 'This city room has been called here'); // we could send email here to user
            // email user
        });
    }
}

