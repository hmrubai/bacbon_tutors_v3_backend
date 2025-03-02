<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reference extends Model
{
    use HasFactory;

    // Since the table name is not the default plural "references", we explicitly define it.
    protected $table = 'reference';

    protected $fillable = [
        'name',
        'designation',
        'organization',
        'phone',
        'email',
        'user_id',
    ];
}
