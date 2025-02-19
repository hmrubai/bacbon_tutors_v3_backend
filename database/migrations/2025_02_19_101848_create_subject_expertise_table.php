<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectExpertiseTable extends Migration
{
    public function up()
    {
        Schema::create('subject_expertise', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('medium_id');   
            $table->unsignedBigInteger('grade_id');    
            $table->unsignedBigInteger('subject_id');  
            $table->unsignedBigInteger('user_id');     
            $table->text('remarks')->nullable();        
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();

            // Optionally, add foreign keys:
            // $table->foreign('medium_id')->references('id')->on('mediums')->onDelete('cascade');
            // $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
            // $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject_expertise');
    }
}
