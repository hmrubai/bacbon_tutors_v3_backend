<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TutorEducationHistory extends Model
{
    use HasFactory,SoftDeletes;

    protected $guarded = [];

    protected $fillable = ['user_id', 'title', 'institute', 'discipline', 'passing_year', 'sequence', 'created_by', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
