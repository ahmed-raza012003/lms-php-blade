<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Workspace extends Model
{
    use HasFactory;

    protected $primaryKey = 'workspace_id';

    protected $fillable = [
        'user_id',
        'workspace_type',
        'name',
        'description',
        'location',
        'address',
        'city',
        'country',
        'latitude',
        'longitude',
        'rating',
        'profile_photo',
        'profile_video',
        'status',
    ];

    protected $casts = [
        'workspace_type' => 'string',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'rating' => 'decimal:2',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}