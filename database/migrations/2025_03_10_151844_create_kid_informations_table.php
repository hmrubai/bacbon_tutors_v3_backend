<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKidInformationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('kid_informations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name'); // required
            $table->integer('age')->nullable();
            $table->enum('gender', ['Male', 'Female', 'Others'])->nullable();
            $table->unsignedBigInteger('class_id'); // required; relates to Grade
            $table->string('institute')->nullable();
            $table->timestamps();

            // Optionally, add foreign key constraints:
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->foreign('class_id')->references('id')->on('grade')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kid_informations');
    }
}
