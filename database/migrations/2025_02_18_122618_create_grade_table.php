<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeTable extends Migration
{
    public function up()
    {
        Schema::create('grade', function (Blueprint $table) {
            $table->id();
            $table->string('name_en'); // required
            $table->string('name_bn')->nullable(); // optional
            $table->text('remarks')->nullable();   // optional
            $table->unsignedBigInteger('medium_id'); // required, reference to mediums table
            $table->timestamps();

            // Optionally, add a foreign key constraint if desired:
            // $table->foreign('medium_id')->references('id')->on('mediums')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('grade');
    }
}
