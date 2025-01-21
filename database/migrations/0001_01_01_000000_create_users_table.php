<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('username')->unique()->nullable();
            $table->string('referral_code')->unique()->nullable();
            $table->string('referred_code')->unique()->nullable();
            $table->string('tutor_code')->unique()->nullable();
            $table->string('primary_number')->unique()->nullable();
            $table->string('alternate_number')->nullable();
            $table->string('profile_image')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('religion')->nullable();
            $table->string('fathers_name')->nullable();
            $table->string('mothers_name')->nullable();
            $table->string('father_number')->nullable();
            $table->string('mothar_number')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Other'])->default('Other');
            $table->string('marital_status')->nullable();
            $table->string('blood_group')->nullable();
            $table->text('bio')->nullable();

            $table->unsignedBigInteger('class_id')->nullable();
            // Present Address fields
            $table->unsignedBigInteger('present_division_id')->nullable();
            $table->unsignedBigInteger('present_district_id')->nullable();
            $table->unsignedBigInteger('present_area_id')->nullable();
            $table->text('present_address')->nullable();

            // Permanent Address fields
            $table->unsignedBigInteger('permanent_division_id')->nullable();
            $table->unsignedBigInteger('permanent_district_id')->nullable();
            $table->unsignedBigInteger('permanent_area_id')->nullable();
            $table->text('permanent_address')->nullable();
            $table->bigInteger('organization_id')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_kid')->default(0);
            $table->boolean('is_account_verified')->default(0);
            $table->boolean('is_foreigner')->default(0);
            $table->boolean('is_bacbon_certified')->default(0);
            $table->enum('user_type', ['Student', 'Teacher', 'Admin', 'Kid', 'Guardian'])->default('Teacher');
            $table->string('device_id')->nullable();
            $table->string('fcm_id')->nullable();
            $table->string('nid_no')->nullable();
            $table->string('birth_certificate_no')->nullable();
            $table->string('profession')->nullable();
            $table->string('passport_no')->nullable();
            $table->string('intro_video')->nullable();
            $table->string('bacbon_rank')->nullable();
            $table->string('profile_progress')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
