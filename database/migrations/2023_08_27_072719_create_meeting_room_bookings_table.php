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
        Schema::create('meeting_room_bookings', function (Blueprint $table) {
            $table->id();
            $table->dateTime('start_time', $precision = 0);
            $table->dateTime('end_time', $precision = 0);
            $table->string('status')->nullable()->default("Pending");

            $table->unsignedBigInteger('user_id'); // Foreign key column
            $table->foreign('user_id')->references('id')->on('users'); // Define foreign key

            $table->unsignedBigInteger('room_id'); // Foreign key column
            $table->foreign('room_id')->references('id')->on('rooms'); // Define foreign key
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meeting_room_bookings');
    }
};
