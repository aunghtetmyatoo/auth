<?php

namespace App\Models;

use App\Models\User;
use App\Traits\Uuid;
use App\Models\History;
use App\Models\TransactionType;
use App\Models\WithdrawRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawTransaction extends Model
{
    use HasFactory, Uuid;
    protected $table = 'withdraw_transactions';
    protected $fillable = [
        'user_id',
        'transaction_type_id',
        'withdraw_request_id',
        'reference_id',
        'amount',
        'handling_fees',
        'from_amount_status',
        'to_amount_status',
        'remark'
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class);
    }

    public function withdraw_request()
    {
        return $this->belongsTo(WithdrawRequest::class);
    }

    public function history()
    {
        return $this->belongsTo(History::class);
    }
}
