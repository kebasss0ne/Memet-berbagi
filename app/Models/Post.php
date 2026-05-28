<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // PASTIKAN TULISANNYA 'protected' DAN DI LUAR FUNGSI APA PUN
    protected $fillable = [
        'user_id', 
        'content', 
        'type'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}