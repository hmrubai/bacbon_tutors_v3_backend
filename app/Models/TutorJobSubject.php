<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorJobSubject extends Model
{
    protected $table = "tutor_job_subjects";
    protected $fillable = [
        'tutor_job_id',
        'subject_id',
    ];

    public function job()
    {
        return $this->belongsTo(\App\Models\TutorJob::class, 'tutor_job_id');
    }

    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }
}
