<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorSchedule extends Model
{
    use HasFactory;

    protected $table = 'tutor_schedules';

    protected $fillable = [
        'day_of_week',
        'morning',
        'afternoon',
        'evening',
        'user_id',
    ];
    protected $casts = [
        'morning' => 'boolean',
        'afternoon' => 'boolean',
        'evening' => 'boolean',
    ];
}
