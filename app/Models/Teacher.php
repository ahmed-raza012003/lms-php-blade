<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $primaryKey = 'teacher_id';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'bio',
        'profile_photo',
        'profile_video',
        'subjects',
        'school_levels',
        'hourly_rate',
        'availability',
        'rating',
        'payment_info',
        'highest_qualification',
        'years_of_experience',
    ];

    protected $casts = [
        'subjects' => 'array',
        'school_levels' => 'array',
        'hourly_rate' => 'decimal:2',
        'availability' => 'array',
        'rating' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function degrees()
    {
        return $this->hasMany(TeacherDegree::class, 'teacher_id');
    }

    public function certifications()
    {
        return $this->hasMany(TeacherCertification::class, 'teacher_id');
    }
}
