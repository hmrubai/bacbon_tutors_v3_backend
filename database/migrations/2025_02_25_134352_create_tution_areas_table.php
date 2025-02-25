<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTutionAreasTable extends Migration
{
    public function up(): void
    {
        Schema::create('tution_areas', function (Blueprint $table) {
            $table->id();
            $table->double('lat', 15, 8);    // required latitude
            $table->double('long', 15, 8);   // required longitude
            $table->unsignedBigInteger('user_id'); // uploader's ID
            $table->text('address')->nullable();    // optional address
            $table->unsignedBigInteger('division_id')->nullable(); // optional
            $table->unsignedBigInteger('district_id')->nullable(); // optional
            $table->unsignedBigInteger('upazila_id')->nullable();  // optional
            $table->unsignedBigInteger('union_id')->nullable();    // optional
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tution_areas');
    }
}
