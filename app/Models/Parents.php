<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $primaryKey = 'parent_id';

    protected $fillable = [
        'user_id',
      'contact_phone',
        'bio',
        'profile_photo',
        'payment_info',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // public function students()
    // {
    //     return $this->hasMany(Student::class, 'parent_id');
    // }
}