<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Room extends Model
{
    use HasFactory;
    // protected $table = 'my_rooms';
    // protected $primaryKey = 'room_id';
    // public $timestamps = false;
    // protected $connection = 'sqlite';

    public function cities()
    {
        return $this->belongsToMany(City::class);
    }

    // Room morph to Many (has many) Comments
    public function comments(): MorphMany
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }

    // users who liked this room
    public function likers()
    {
        return $this->morphToMany(User::class, 'likeable', 'likeables', 'likeable_id', 'user_id')
                    ->withTimestamps();
    }

}
