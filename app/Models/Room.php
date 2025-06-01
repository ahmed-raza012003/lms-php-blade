<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $primaryKey = 'room_id';

    protected $fillable = [
        'workspace_id',
        'name',
        'description',
        'size',
        'price_per_hour',
        'capacity',
        'profile_photo',
        'profile_video',
        'status',
    ];

    protected $casts = [
        'price_per_hour' => 'decimal:2',
        'capacity' => 'integer',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function workspace()
    {
        return $this->belongsTo(Workspace::class, 'workspace_id');
    }
    public function bookings()
    {
        return $this->hasMany(RoomBooking::class, 'room_id');
    }
}
