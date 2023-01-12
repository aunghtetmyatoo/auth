<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashOutRequest extends Model
{
    use HasFactory,Uuid;

    protected $fillable = ["transaction_type_id", "account_name","account_number","amount","user_id","status","status_updated_by"];

}
