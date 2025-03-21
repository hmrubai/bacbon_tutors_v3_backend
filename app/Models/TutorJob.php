<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TutorJob extends Model
{
    use HasFactory;

    // Use the table tutor_jobs instead of the default "jobs"
    protected $table = 'tutor_jobs';

    protected $casts = [
        'salary_amount' => 'integer',
    ];

    protected $fillable = [
        'job_id',
        'student_id',
        'kid_id',
        'user_id',
        'job_title',
        'slot_days',
        'slot_type',
        'salary_amount',
        'gender',
        'salary_type',
        'tutoring_time',
        'medium_id',
        'subject_id',
        'note',
        'tuition_type',
        'grade_id',
        'division_id',
        'district_id',
        'upazila_id',
        'area_id',
        'negotiable',
    ];



    /**
     * Get the medium associated with the job.
     */
    public function medium()
    {
        return $this->belongsTo(\App\Models\Medium::class, 'medium_id');
    }

    /**
     * Get the subject associated with the job.
     */
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'tutor_job_subjects', 'tutor_job_id', 'subject_id');
    }

    //institutes
    public function institutes()
    {
        return $this->belongsToMany(Institute::class, 'tutor_job_institutes', 'tutor_job_id', 'institute_id');
    }

    /**
     * Get the kid information associated with the job.
     */
    public function kid()
    {
        return $this->belongsTo(\App\Models\KidInformation::class, 'kid_id');
    }

    public function grade()
    {
        return $this->belongsTo(Grade::class, 'grade_id');
    }
}
