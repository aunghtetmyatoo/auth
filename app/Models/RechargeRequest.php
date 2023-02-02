<?php

namespace App\Models;

use App\Traits\Uuid;
use App\Models\RechargeChannel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RechargeRequest extends Model
{
    use HasFactory, Uuid;

    protected $table = "recharge_requests";

    protected $fillable = ["sequence", "recharge_channel_id", "reference_id", "user_id", "requested_amount", "confirmed_amount", "completed_by", "rate", "received_amount", "received_from", "screenshot", "description", "read_at", 'expired_at', "confirmed_at", "status"];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment_type()
    {
        return $this->belongsTo(PaymentType::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
    public function recharge_channel()
    {
        return $this->belongsTo(RechargeChannel::class);
    }
}
