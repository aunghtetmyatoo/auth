<?php

namespace App\Models;

use App\Models\Admin;
use App\Models\WithdrawChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawRequest extends Model
{
    use HasFactory;
    protected $table = "withdraw_requests";
    protected $fillable = ["user_id", "transaction_time", "transaction_screenshot", "admin_transfer_status", "admin_id", "payment_type_id", "amount"];

    public function users()
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
