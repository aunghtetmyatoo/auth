<?php

namespace App\Models;

use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory,Uuid;

    protected $table = "histories";

    public function historiable()
    {
        return $this->morphTo();
    }

}
