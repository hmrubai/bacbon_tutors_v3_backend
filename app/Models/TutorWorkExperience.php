<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorWorkExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'employment_type',
        'designation',
        'company_name',
        'currently_working',
        'start_date',
        'end_date',
    ];
}
