<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    // Specify the table name explicitly since the default pluralization might not match.
    protected $table = 'grade';

    protected $fillable = [
        'name_en',
        'name_bn',
        'remarks',
        'medium_id',
    ];
}
