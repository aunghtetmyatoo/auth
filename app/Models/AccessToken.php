<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessToken extends Model
{
    use HasFactory, Uuid;
    /**
     * The attributes that are not assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identifier',
        'token',
        'action',
        'expired_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'token',
    ];
}
