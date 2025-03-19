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
        Schema::table('tutor_jobs', function (Blueprint $table) {
            $table->enum('tuition_type', ['Online', 'Offline'])->default('Online');
            $table->foreignId('grade_id')->nullable();
            $table->foreignId('division_id')->nullable();
            $table->foreignId('district_id')->nullable();
            $table->foreignId('upazila_id')->nullable();
            $table->foreignId('area_id')->nullable();
            $table->string('institute_ids')->nullable();
            $table->boolean('negotiable')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tutor_jobs', function (Blueprint $table) {
            //
        });
    }
};
