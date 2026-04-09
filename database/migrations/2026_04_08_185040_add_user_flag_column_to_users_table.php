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
        // add user_flag column to users table to identify user type
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('user_flag_id')
                ->default(1)
                ->constrained('user_flags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // drop user_flag column from users table
            $table->dropColumn('user_flag');
        });
    }
};
