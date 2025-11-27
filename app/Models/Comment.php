<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $casts = [
        'rating' => 'float',
    ];

    // protected $fillable = ['rating', 'content', 'user_id'];
    protected $guarded = []; // everything open for mass assignment 

    // Events
    // retrieved, creating, created, updating, updated, saving, saved, deleting, deleted, restoring, restored
    // When issuing a mass update or delete via Eloquent, 
    // the saved, updated, deleting, and deleted model events 
    // will not be fired for the affected models. 
    // This is because the models are never actually retrieved when issuing a mass update or delete.

    // How to listen to fired events? 2 Ways 
    // Way 1
    // protected $dispatchesEvents = [
    //     'saved' => 'class to handle saved event',
    //     'deleted' => 'class to deleted saved event'
    // ];
    
    // Way 2
    protected static function booted()
    {
        // every time do get() on Comment 
        static::retrieved(function ($comment) { 
            // this code will be executed  - comment rating echoed 
            // echo $comment->rating;
        });
    }

    // Accessor 
    // this is special fucktion get rating clumn and Attribute  get Rating Attribute
    // public function getRatingAttribute($value)
    // {
    //     return $value + 10; // increaes rating by 10 when get() or find is called  you could update comment or do something else
    // }
    
    // Accessor WhoWaht is not a column 
    public function getWhoWhatAttribute()
    {
        return "user {$this->user_id} rates {$this->rating}";
    }

    // Mutators can be useful for hasing passwords 
    public function setRatingAttribute($value)
    {
        // increase value or rating by 1 before saving it in db
        $this->attributes['rating'] = $value + 1; 
    }


    // executed automatically when calling Comment model
    // protected static function booted()
    // {
    //     static::addGlobalScope('rating', function (Builder $builder) {
    //         $builder->where('rating', '>', 2);
    //     });
    // }

    // public function scopeRating($query, int $value = 4)
    // {
    //     return $query->where('rating', '>', $value);
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * From Comment Get the userAddress through User
     */
    // public function userAddress(): HasOneThrough
    // {
    //     return $this->hasOneThrough(
    //         Address::class, // final model
    //         User::class     // through model
    //     );
    // }

    public function userAddress(): HasOne
    {
        return $this->hasOne(Address::class, 'user_id', 'user_id'); // Comments.user_id and Address.user_id
    }

    /**
     * Polymorphic parent (Room, image, ...)
     */
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

}
