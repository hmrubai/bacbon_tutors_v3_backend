<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    use HasFactory;

    // Use the same table as the User model
    protected $table = 'users';

    // Only allow updating these fields
    protected $fillable = [
        'name',
        'profile_image',
        'primary_number',
        'alternate_number',
        'email',
        'date_of_birth',
        'religion',
        'fathers_name',
        'mothers_name',
        'gender',
        'blood_group',
        'bio',
    ];
}
