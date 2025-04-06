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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'date_of_birth' => 'date',
            'is_kid' => 'boolean',
            'is_account_verified' => 'boolean',
            'is_foreigner' => 'boolean',
            'is_bacbon_certified' => 'boolean',
            'is_password_set' => 'boolean',
            'class_id' => 'integer',
            'present_division_id' => 'integer',
            'present_district_id' => 'integer',
            'present_area_id' => 'integer',
            'permanent_division_id' => 'integer',
            'permanent_district_id' => 'integer',
            'permanent_area_id' => 'integer',
            'organization_id' => 'integer',
            'created_by' => 'integer',
            'profile_progress' => 'integer',


        ];
    }
}
