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
        Schema::create('coaching_log_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coaching_log_id')->constrained('coaching_logs');
            $table->foreignId('agent_id')->constrained('users');
            $table->foreignId('agent_team_id')->constrained('teams');
            $table->date('date_coached')->nullable();
            $table->date('next_date_coached')->nullable();
            $table->mediumText('goal')->nullable();
            $table->mediumText('reality')->nullable();
            $table->mediumText('option')->nullable();
            $table->mediumText('will')->nullable();
            $table->integer('status')->default(0); //0 pending/new | 1 accepted | 2 completed | 3 declined | 4 canceled 
            $table->integer('follow_through')->default(0); //0 false | 1 true
            $table->string('channel')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coaching_log_details');
    }
};
