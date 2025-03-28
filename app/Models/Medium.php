<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medium extends Model
{
    use HasFactory;
    
    // Specify the table name to match your migration
    protected $table = 'mediums';

    protected $fillable = [
        'title_en',
        'title_bn',
        'remarks',
    ];
}
