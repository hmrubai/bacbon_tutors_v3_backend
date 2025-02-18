<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // The default table will be "subjects" (plural of Subject), so no need to specify unless you want to be explicit.
    protected $fillable = [
        'name_en',
        'name_bn',
        'remarks',
        'medium_id',
        'grade_id',
    ];
}
