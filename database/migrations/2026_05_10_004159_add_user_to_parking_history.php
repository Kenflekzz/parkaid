<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parking_history', function (Blueprint $table) {
            $table->foreignId('user_id')
                  ->nullable()
                  ->after('slot_id')
                  ->constrained('users')
                  ->onDelete('set null')
                  ->comment('User who triggered this status change');
        });
    }

    public function down(): void
    {
        Schema::table('parking_history', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};