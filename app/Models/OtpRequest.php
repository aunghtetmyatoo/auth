<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtpRequest extends Model
{
    use HasFactory;
    protected $table = "otp_requests";
    protected $fillable = ["phone_number", "browser_id", "action"];
}
