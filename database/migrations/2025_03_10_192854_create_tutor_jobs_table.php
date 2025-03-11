<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutorJobsTable extends Migration
{
    public function up(): void
    {
        Schema::create('tutor_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('kid_id')->nullable();
            $table->unsignedBigInteger('user_id'); // current user (the job owner)
            $table->string('job_title');
            $table->integer('slot_days');
            $table->enum('slot_type', ['Month', 'Week']);
            $table->decimal('salary_amount', 10, 2);
            $table->enum('gender', ['Male', 'Female', 'Others']);
            $table->enum('salary_type', ['Hour', 'Week', 'Month']);
            // tutoring_time stored as a time type (format: H:i:s)
            $table->time('tutoring_time');
            $table->unsignedBigInteger('medium_id');
            $table->unsignedBigInteger('subject_id');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tutor_jobs');
    }
}
