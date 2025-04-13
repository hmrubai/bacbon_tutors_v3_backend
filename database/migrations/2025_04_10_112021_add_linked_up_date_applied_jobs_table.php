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
        Schema::table('applied_jobs', function (Blueprint $table) {
            $table->timestamp('linked_up_start_at')->nullable()->after('linked_up_with_id');
            $table->timestamp('linked_up_end_at')->nullable()->after('linked_up_start_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applied_jobs', function (Blueprint $table) {
            $table->dropColumn('linked_up_start_at');
            $table->dropColumn('linked_up_end_at');
        });
    }
};
