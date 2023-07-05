<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAccount extends Model
{
    use HasFactory,Uuid;

    protected $table = 'cash_accounts';

    protected $fillable = ['amount','account_name','reference_id'];


}
