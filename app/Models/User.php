<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Http\Traits\OrganizationScopedTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, HasRoles, Notifiable,SoftDeletes;
    use OrganizationScopedTrait;

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
    ];

    protected $hidden = [
        'password',
        'remember_token',
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
        ];
    }


    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function subjectExpertise()
    {
        return $this->hasMany(SubjectExpertise::class);
    }

    public function workExperiences()
    {
        return $this->hasMany(TutorWorkExperience::class);
<<<<<<< HEAD

    }

    // Relation for References
=======
    }

>>>>>>> 57547c02077f4d032de417bde28a5fc0ad502528
    public function references()
    {
        return $this->hasMany(Reference::class);
    }

<<<<<<< HEAD
    // Relation for Address (assuming one-to-one)
=======
>>>>>>> 57547c02077f4d032de417bde28a5fc0ad502528
    public function address()
    {
        return $this->hasOne(Address::class);
    }

<<<<<<< HEAD
    // Relation for Documents
=======
>>>>>>> 57547c02077f4d032de417bde28a5fc0ad502528
    public function documents()
    {
        return $this->hasMany(Document::class);
    }

<<<<<<< HEAD
    // Relation for Tution Areas
=======
>>>>>>> 57547c02077f4d032de417bde28a5fc0ad502528
    public function tutionAreas()
    {
        return $this->hasMany(TutionArea::class);
    }

<<<<<<< HEAD
    // Relation for Tutor Schedules
=======
>>>>>>> 57547c02077f4d032de417bde28a5fc0ad502528
    public function tutorSchedules()
    {
        return $this->hasMany(TutorSchedule::class);
    }
<<<<<<< HEAD

    // // Example: Relation for Grade (if the user has a grade via the 'class_id' column)
    // public function grade()
    // {
    //     return $this->belongsTo(Grade::class, 'class_id');
    // }
=======
>>>>>>> 57547c02077f4d032de417bde28a5fc0ad502528
}
