<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TutorFavorite extends Model
{
    protected $table = "tutor_favorites";
    protected $fillable = [
        'tutor_id',
        'user_id',
    ];

    public function tutor()
    {
        return $this->belongsTo(User::class, 'tutor_id', 'id');
    }


}
