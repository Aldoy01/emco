<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginAudit extends Model
{
    protected $fillable = [
        'email',
        'user_id',
        'ip_address',
        'user_agent',
        'status',
        'context',
    ];
}
