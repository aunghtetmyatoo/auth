<?php

namespace App\Models;

use App\Models\User;
use App\Models\History;
use App\Models\TransactionType;
use App\Models\WithdrawRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WithdrawTransaction extends Model
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

    public function withdraw_request()
    {
        return $this->belongsTo(WithdrawRequest::class);
    }

    public function history()
    {
        return $this->belongsTo(History::class);
    }
}
