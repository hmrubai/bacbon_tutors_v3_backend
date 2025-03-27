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
        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('details');
            $table->string('thumbnail')->nullable();
            $table->bigInteger('user_id');
            $table->string('redirection_path')->nullable();
            $table->json('data')->nullable();
            $table->boolean('is_for_all')->default(0);
            $table->boolean('is_read')->default(0);
            $table->timestamp('read_at')->nullable();
            $table->bigInteger('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('push_notifications');
    }
};
