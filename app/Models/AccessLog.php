<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    protected $fillable = [
        'ip',
        'user_email',
        'method',
        'path',
        'status',
        'failed_login',
        'access_time'
    ];
}
