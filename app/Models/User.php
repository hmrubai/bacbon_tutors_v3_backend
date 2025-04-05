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
        'subject'
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
            'profile_progress' => 'integer',
            

        ];
    }
    // profile_progress default value 0
    protected function atributosPropiosParaJson(): array
    {
        return [
            'profile_progress' => 0,
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
    }

    public function educationHistory()
    {
        return $this->hasMany(TutorEducationHistory::class);
    }

    public function references()
    {
        return $this->hasMany(Reference::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function tutionAreas()
    {
        return $this->hasMany(TutionArea::class);
    }

    public function tutorSchedules()
    {
        return $this->hasMany(TutorSchedule::class);
    }

    public function bookmarkedJobs()
    {
        return $this->belongsToMany(TutorJob::class, 'tuition_bookmarks', 'user_id', 'tutor_job_id');
    }

    public function presentDivision()
    {
        return $this->belongsTo(Division::class, 'present_division_id');
    }

    public function presentDistrict()
    {
        return $this->belongsTo(District::class, 'present_district_id');
    }

    public function presentArea()
    {
        return $this->belongsTo(Union::class, 'present_area_id');
    }

    public function permanentDivision()
    {
        return $this->belongsTo(Division::class, 'permanent_division_id');
    }

    public function permanentDistrict()
    {
        return $this->belongsTo(District::class, 'permanent_district_id');
    }

    public function presentUpazila()
    {
        return $this->belongsTo(Upazila::class, 'present_upazila_id');
    }

    public function permanentUpazila()
    {
        return $this->belongsTo(Upazila::class, 'permanent_upazila_id');
    }

    public function permanentArea()
    {
        return $this->belongsTo(Union::class, 'permanent_area_id');
    }
}
