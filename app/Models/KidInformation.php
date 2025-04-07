<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KidInformation extends Model
{
    use HasFactory;

    protected $table = 'kid_informations';

    protected $fillable = [
        'user_id',
        'name',
        'age',
        'gender',
        'class_id',
        'institute',
        'medium_id'
    ];

    /**
     * Get the grade (class) associated with the kid.
     */
    public function grade()
    {
        return $this->belongsTo(\App\Models\Grade::class, 'class_id');
    }
    public function medium()
    {
        return $this->belongsTo(\App\Models\Medium::class, 'medium_id');
    }
}
