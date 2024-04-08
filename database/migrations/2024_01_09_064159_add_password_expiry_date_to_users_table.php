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
            $table->timestamp('password_expiry_date')->nullable();
            $table->timestamp('password_updated_at')->nullable();
            $table->boolean('is_locked')->default(false);
            $table->string('reset_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('password_expiry_date');
            $table->dropColumn('password_updated_at');
            $table->dropColumn('is_locked');
        });
    }
};
