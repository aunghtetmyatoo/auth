<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Traits\Uuid;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Uuid;

    public function findForPassport($phone_number)
    {
        return $this->where('phone_number', $phone_number)->first();
    }

    protected $fillable = [
        'name',
        'phone_number',
        'password',
        'reference_id',
        'device_id',
        'amount',
        'coins',
        'registered_at',
        'payment_account_number',
        'payment_account_name',
        'payment_type_id'
    ];
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
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


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

    public function game_types()
    {
        return $this->belongsToMany(GameType::class)->withTimestamps();
    }

    public function coin_fill_requests()
    {
        return $this->hasMany(CoinFillRequest::class);
    }

    public function histories()
    {
        return $this->morphMany('App\History', 'historiable');
    }
}
