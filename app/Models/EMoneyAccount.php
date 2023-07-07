<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EMoneyAccount extends Model
{
    //CashGl
    use HasFactory,Uuid;

    protected $table = 'e_money_accounts';

    protected $fillable = ['amount','account_name','reference_id'];

    public function histories()
    {
        return $this->morphMany(History::class, 'historiable');
    }

    public function monitor_logs()
    {
        return $this->morphMany(MonitorLog::class, 'monitor_loggable');
    }

}
