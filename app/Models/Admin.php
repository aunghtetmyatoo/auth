<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
        'sequence',
        'frozen_at_bk',
        'bk_otp_mistook_at',
        'bk_otp_mistake_count',
        'password_mistook_at',
        'password_mistake_count',
        'mfa_mistook_at',
        'mfa_mistake_count',
        'last_logged_in_at',
        'first_logged_in_at',
        'password_changed_at',
        'language',
        'created_at',
        'updated_at',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'frozen_at_bk' => 'datetime',
        'bk_otp_mistook_at' => 'datetime',
        'password_mistook_at' => 'datetime',
        'mfa_mistook_at' => 'datetime',
        'last_logged_in_at' => 'datetime',
        'first_logged_in_at' => 'datetime',
        'password_changed_at' => 'datetime',
        'registered_at' => 'datetime',
    ];
}
