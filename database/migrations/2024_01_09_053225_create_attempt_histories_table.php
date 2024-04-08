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
        Schema::create('attempt_histories', function (Blueprint $table) {
            $table->id();
            $table->string('hostname');
            $table->string('mac_address');
            $table->string('account_attempted');
            $table->text('ipconfig_data');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attempt_histories');
    }
};
