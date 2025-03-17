<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectExpertise extends Model
{
    use HasFactory;

    // Explicitly specify the table name
    protected $table = 'subject_expertise';

    protected $fillable = [
        'medium_id',
        'grade_id',
        'subject_id',
        'user_id',
        'remarks',
        'status',
        'tuition_type',
        'rate',
        'fee',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    // Relationship to Medium
    public function medium()
    {
        return $this->belongsTo(\App\Models\Medium::class, 'medium_id');
    }

    // Relationship to Grade
    public function grade()
    {
        return $this->belongsTo(\App\Models\Grade::class, 'grade_id');
    }

    // Relationship to Subject
    public function subject()
    {
        return $this->belongsTo(\App\Models\Subject::class, 'subject_id');
    }

    public function class_list()
    {
        return $this->hasMany(\App\Models\ExpertiseClassList::class);
        // return $this->belongsTo(\App\Models\ExpertiseClassList::class, 'grade_id');
    }
}
