<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // Explicitly specify the table name since the default plural would be "addresses"
    protected $table = 'address';

    protected $fillable = [
        'present_address',
        'permanent_address',
        'user_id',
    ];
}
