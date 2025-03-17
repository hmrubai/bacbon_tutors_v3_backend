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
        Schema::table('subject_expertise', function (Blueprint $table) {
            $table->enum('tuition_type', ['offline', 'online','both'])->after('subject_id')->nullable();
            $table->enum('rate', ['hourly', 'fixed','monthly','yearly'])->after('tuition_type')->nullable();
            $table->decimal('fee', 8, 2)->after('rate')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subject_expertise', function (Blueprint $table) {
            //
        });
    }
};
