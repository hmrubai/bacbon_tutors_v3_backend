<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferenceTable extends Migration
{
    public function up()
    {
        Schema::create('reference', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // required
            $table->string('designation')->nullable(); // optional
            $table->string('organization'); // required
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('user_id'); // tutor's id
            $table->timestamps();
            
            // Optionally, add a foreign key to the users table:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reference');
    }
}
