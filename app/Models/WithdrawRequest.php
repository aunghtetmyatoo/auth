<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawRequest extends Model
{
    use HasFactory;
    protected $table = "withdraw_requests";
    protected $fillable = ["user_id", "transaction_time", "transaction_screenshot", "admin_transfer_status", "admin_id", "payment_type_id", "amount"];

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
