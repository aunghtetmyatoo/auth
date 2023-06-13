<?php

namespace App\Models;

use App\Traits\Uuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory,Uuid;

    protected $table = "histories";

    protected $fillable = [
        'historiable_id',
        'historiable_type',
        'transactionable_id',
        'transactionable_type',
        'transaction_type_id',
        'reference_id',
        'transaction_amount',
        'amount_before_transaction',
        'amount_after_transaction',
        'is_from',
    ];

    public function historiable()
    {
        return $this->morphTo();
    }

    public function transactionable()
    {
        return $this->morphTo();
    }

    public function setCreatedAtAttribute($value)
    {
        $this->attributes['created_at'] = Carbon::parse($value)->format('Y-m-d H:i:s.u');
    }


}
