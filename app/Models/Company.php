<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    public function users()
    {
        return $this->hasMany(User::class);
    }

    // get reservations through user as user belongs to company
    public function reservations(): HasManyThrough
    {
        return $this->hasManyThrough(
            Reservation::class, // final model
            User::class,        // through model
            'company_id',       // Foreign key on users table pointing to companies
            'user_id',          // Foreign key on reservations table pointing to users
            'id',               // Local key on companies table
            'id'                // Local key on users table
        );
    }
}
