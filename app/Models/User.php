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

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


}
