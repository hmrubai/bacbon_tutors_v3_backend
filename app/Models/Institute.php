<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    protected $fillable = [
        'title',
        'institute_type',
        'is_active',
        'is_selected',
        'created_by',
    ];
}
