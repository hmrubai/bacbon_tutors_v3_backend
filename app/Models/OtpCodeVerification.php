<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OtpCodeVerification extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'otp_code', 'expired_at'];
}
