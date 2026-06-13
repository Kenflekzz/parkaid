<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('management_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action');        // 'slot_added', 'slot_deleted', 'space_added', 'space_deleted'
            $table->string('slot_id')->nullable();
            $table->string('floor')->nullable();
            $table->string('type')->nullable();
            $table->integer('quantity')->default(1); // for bulk adds
            $table->timestamp('logged_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('management_logs');
    }
};