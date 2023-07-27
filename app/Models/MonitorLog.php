<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonitorLog extends Model
{
    use HasFactory,Uuid;

    protected $table = "monitor_logs";

    protected $fillable = [
        'transaction_type_id',
        'monitor_loggable_id',
        'monitor_loggable_type',
        'reference_id',
        'read',
        'transaction_at',
        'difference_amount',
        'error_text',
        'error_status'
    ];

    protected $casts = [
        'read' => 'boolean',
    ];

    public function monitor_loggable()
    {
        return $this->morphTo();
    }

    public function transaction_type()
    {
        return $this->belongsTo(TransactionType::class);
    }
}
