<?php

namespace App\Models;

use App\Models\User;
use App\Models\RechargeRequest;
use App\Models\TransactionType;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RechargeTransaction extends Model
{
    use HasFactory, Uuid;

    protected $fillable = [
        'transaction_type_id',
        'recharge_request_id',
        'user_id',
        'reference_id',
        'amount',
        'remark',
    ];

    public function user()
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
