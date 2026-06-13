<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parking_slots', function (Blueprint $table) {
            $table->id();
            $table->string('slot_id')->unique();        // e.g. 'A-01'
            $table->string('number', 10);               // e.g. '01'
            $table->string('floor');                    // e.g. 'Ground Floor'
            $table->string('type')->default('Regular'); // Regular, Compact, PWD, VIP, EV Charging
            $table->boolean('occupied')->default(false);
            $table->float('distance')->nullable();      // cm from ultrasonic sensor
            $table->string('last_updated')->nullable(); // formatted time string
            $table->timestamps();
        });

        Schema::create('parking_history', function (Blueprint $table) {
            $table->id();
            $table->string('slot_id');
            $table->string('floor');
            $table->string('type')->default('Regular');
            $table->enum('status', ['vacant', 'occupied']);
            $table->float('distance')->nullable();
            $table->timestamp('changed_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parking_slots');
        Schema::dropIfExists('parking_history');
    }
};