<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherDegree extends Model
{
    use HasFactory;

    protected $primaryKey = 'degree_id';

    protected $fillable = [
        'teacher_id',
        'name',
        'file_path',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
