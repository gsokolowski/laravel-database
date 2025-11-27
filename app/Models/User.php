<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'meta' => 'json',
        ];
    }

    public function address(): HasOne
    {
        return $this->hasOne(Address::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    // rooms the user has liked
    public function likedRooms()
    {
        // user -> many rooms via likeables pivot
        return $this->morphedByMany(Room::class, 'likeable', 'likeables', 'user_id', 'likeable_id')
                    ->withTimestamps();
    }

    // images the user has liked
    public function likedImages()
    {
        return $this->morphedByMany(Image::class, 'likeable', 'likeables', 'user_id', 'likeable_id')
                    ->withTimestamps();
    }
}
