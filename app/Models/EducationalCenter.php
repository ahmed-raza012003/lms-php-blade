<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EducationalCenter extends Model
{
    // Specify the fields that can be mass-assigned
    protected $fillable = [
        'name',
        'description',
        'address',
        'website',
        'operating_hours',
        'verification_status',
        'user_id',
    ];

    // Define the relationship with the User model (owner)
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}