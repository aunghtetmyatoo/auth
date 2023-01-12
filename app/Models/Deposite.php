<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposite extends Model
{
    use HasFactory,Uuid;

    protected $fillable = ["name", "account_name", "phone_number", "amount", "transaction_photo", "agent_text", "agent_photo"];

}
