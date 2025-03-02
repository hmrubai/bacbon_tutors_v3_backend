<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_image');  // required
            $table->enum('document_type', ['certificate', 'national_id', 'passport', 'birth_certificate']);
            $table->boolean('approval')->default(false); // by default not approved
            $table->unsignedBigInteger('approved_by')->nullable(); // admin id who approved
            $table->unsignedBigInteger('user_id'); // uploader's id
            $table->boolean('status')->nullable()->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
}
