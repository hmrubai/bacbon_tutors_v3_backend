<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutionArea extends Model
{
    use HasFactory;

    // Specify table name explicitly if necessary.
    protected $table = 'tution_areas';

    protected $fillable = [
        'lat',
        'long',
        'user_id',
        'address',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'status',
    ];
    protected $casts = [
        'status' => 'boolean',
    ];
}
