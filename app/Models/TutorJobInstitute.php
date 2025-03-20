<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorJobInstitute extends Model
{
    protected $table = "job_tutor_institutes";
    protected $fillable = ['tutor_job_id', 'institute_id'];
}
