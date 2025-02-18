<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectsTable extends Migration
{
    public function up()
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name_en'); // required
            $table->string('name_bn')->nullable(); // optional
            $table->text('remarks')->nullable();   // optional
            $table->unsignedBigInteger('medium_id'); // optional; foreign key to mediums (if desired)
            $table->unsignedBigInteger('grade_id'); // required; foreign key to grades (if desired)
            $table->timestamps();

            // Optionally add foreign keys:
            // $table->foreign('medium_id')->references('id')->on('mediums')->onDelete('cascade');
            // $table->foreign('grade_id')->references('id')->on('grades')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('subjects');
    }
}
