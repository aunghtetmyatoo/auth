<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Models\Admin;
use App\Models\WithdrawChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawRequest extends Model
{
    use HasFactory, Uuid;

    protected $table = "withdraw_requests";
    protected $fillable = ["user_id", "sequence", "withdraw_channel_id", "reference_id", "rate","bank_name" , "payee","account_number","amount","handling_fee","screenshot"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function withdraw_channel()
    {
        return $this->belongsTo(WithdrawChannel::class);
    }
}
