<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /*
    |--------------------------------------------------------------------------
    | Mass Assignable
    |--------------------------------------------------------------------------
    | Jangan kasih role / otp / verified bebas diisi user
    */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */
    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    /*
    |--------------------------------------------------------------------------
    | Cast
    |--------------------------------------------------------------------------
    */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expired_at'    => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relation
    |--------------------------------------------------------------------------
    */
    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Helper
    |--------------------------------------------------------------------------
    */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
