<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashGl extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'amount',
        'status'
    ];

}
