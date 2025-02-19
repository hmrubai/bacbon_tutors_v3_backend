<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectExpertise extends Model
{
    use HasFactory;

    // Explicitly specify the table name (to avoid Eloquent pluralization issues)
    protected $table = 'subject_expertise';

    protected $fillable = [
        'medium_id',
        'grade_id',
        'subject_id',
        'user_id',
        'remarks',
        'status',
    ];
}
