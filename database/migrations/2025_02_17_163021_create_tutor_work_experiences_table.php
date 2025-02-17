<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutorWorkExperiencesTable extends Migration
{
    public function up()
    {
        Schema::create('tutor_work_experiences', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');  // Tutor ID
            $table->bigInteger('employment_type');  // Employee Type ID from your Employee Type CRUD
            $table->string('designation');  // Required
            $table->string('company_name'); // Required
            $table->boolean('currently_working')->default(false); // true or false
            $table->date('start_date'); // Required start date
            $table->date('end_date')->nullable(); // End date (nullable if currently working)
            $table->timestamps();
            
            // Optionally, add foreign key constraints if desired:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('employment_type')->references('id')->on('employee_types')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tutor_work_experiences');
    }
}
