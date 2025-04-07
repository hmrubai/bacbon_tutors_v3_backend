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
        Schema::table('kid_informations', function (Blueprint $table) {
            $table->foreignId('medium_id')->nullable()->after('institute');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kid_informations', function (Blueprint $table) {
            $table->dropColumn('medium_id');
        });
    }
};
