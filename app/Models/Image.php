<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Image extends Model
{
    /** @use HasFactory<\Database\Factories\ImageFactory> */
    use HasFactory;

    protected $fillable = [
        'filename',
        'path',
        'mime_type',
        'alt',
        'size',
    ];

    /**
     * Polymorphic parent (User, City, ...)
     */
    public function imageable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Helper: return storage URL if you're using the "public" disk
     */
    public function url(): string
    {
        // adjust disk if you use s3 or other
        // return \Illuminate\Support\Facades\Storage::disk('public')->url($this->path);
        return asset('storage/' . $this->path);
    }

    // Image morp to hMany (has many) Comments
    public function comments(): MorphMany
    {
        return $this->morphMany('App\Models\Comment', 'commentable');
    }

    public function likers()
    {
        return $this->morphToMany(User::class, 'likeable', 'likeables', 'likeable_id', 'user_id')
                    ->withTimestamps();
    }
}
