<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RechargeRequest extends Model
{
    use HasFactory,Uuid;

    protected $table = "recharge_requests";

    protected $fillable = ["user_id", "transaction_screenshot","status","admin_id","payment_type_id"];

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
}
