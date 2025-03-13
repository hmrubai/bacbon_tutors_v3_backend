<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpertiseClassList extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'subject_expertise_id',
        'grade_id',
        'user_id',
        'status'
    ];

    protected $casts = [
        'status' => 'boolean'
    ];

    public function subjectExpertise()
    {
        return $this->belongsTo(SubjectExpertise::class);
    }
}
