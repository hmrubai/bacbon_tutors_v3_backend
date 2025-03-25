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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('present_upazila_id')->nullable()->after('present_area_id');
            $table->foreignId('permanent_upazila_id')->nullable()->after('permanent_area_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('present_upazila_id');
            $table->dropColumn('permanent_upazila_id');
        });
    }
};
