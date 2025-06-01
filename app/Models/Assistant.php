<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assistant extends Model
{
    use HasFactory;

    protected $primaryKey = 'assistant_id';

    protected $fillable = [
        'assistant_user_id',
        'user_id',
        'authority_level',
    ];

    protected $casts = [
        'authority_level' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function assistantUser()
    {
        return $this->belongsTo(User::class, 'assistant_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}