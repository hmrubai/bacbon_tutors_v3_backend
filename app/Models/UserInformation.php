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
        'email',
        'username',
        'referral_code',
        'referred_code',
        'tutor_code',
        'primary_number',
        'alternate_number',
        'profile_image',
        'date_of_birth',
        'religion',
        'fathers_name',
        'mothers_name',
        'father_number',
        'mothar_number',
        'gender',
        'marital_status',
        'blood_group',
        'bio',
        'class_id',
        'present_division_id',
        'present_district_id',
        'present_area_id',
        'present_address',
        'permanent_division_id',
        'permanent_district_id',
        'present_upazila_id',
        'permanent_upazila_id',
        'permanent_area_id',
        'permanent_address',
        'organization_id',
        'is_active',
        'is_kid',
        'is_account_verified',
        'is_foreigner',
        'is_bacbon_certified',
        'user_type',
        'device_id',
        'fcm_id',
        'nid_no',
        'birth_certificate_no',
        'profession',
        'passport_no',
        'intro_video',
        'bacbon_rank',
        'profile_progress',
        'email_verified_at',
        'created_by',
        'password',
        'is_password_set',
        'department',
        'subject',
        'institute_id',
        'is_online'
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
            'is_online'=>'boolean',


        ];
    }
}
