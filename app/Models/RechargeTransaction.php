<?php

namespace App\Models;

use App\Models\User;
use App\Models\RechargeRequest;
use App\Models\TransactionType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RechargeTransaction extends Model
{
    use HasFactory;
    public function users()
    {
        return $this->belongsTo(User::class);
    }


    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function recharge_request()
    {
        return $this->belongsTo(RechargeRequest::class);
    }

    public function history()
    {
        return $this->belongsTo(History::class);
    }
}
