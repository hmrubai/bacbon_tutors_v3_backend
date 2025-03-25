<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppliedJob extends Model
{
    protected $fillable = [
        'tutor_id',
        'job_id',
        'cover_letter',
        'expected_salary',
        'status',
        'reviewed_by',
        'reviewed_at',
        'review_note',
        'applied_at',
        'is_linked_up',
        'linked_up_with_id',
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function tutorJobs()
    {
        return $this->belongsTo(TutorJob::class, 'job_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function linkedWith()
    {
        return $this->belongsTo(User::class, 'linked_up_with_id');
    }
}
