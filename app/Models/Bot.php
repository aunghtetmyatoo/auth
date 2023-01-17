<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bot extends Model
{
    use HasFactory, Uuid;

    protected $table = 'bots';

    protected $fillable = [
        'name',
        'amount',
        'coin',
    ];
}
