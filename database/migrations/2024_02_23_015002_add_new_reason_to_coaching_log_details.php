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
        Schema::table('coaching_log_details', function (Blueprint $table) {
            $table->integer('follow_coaching_log_parent');
            $table->string('reason');
            $table->interger('follow_through_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coaching_log_details', function (Blueprint $table) {
            //
        });
    }
};
