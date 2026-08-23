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
        Schema::create('event_meetings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->date('date');
            $table->time('time');
            $table->string('location');
            $table->text('description')->nullable();
            $table->enum('status', ['scheduled', 'ongoing', 'completed', 'cancelled'])->default('scheduled');
            $table->longText('minutes')->nullable();
            $table->string('minutes_file')->nullable();
            $table->timestamps();
        });

        Schema::create('event_meeting_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_meeting_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['hadir', 'izin', 'sakit', 'alpa'])->default('alpa');
            $table->string('reason')->nullable();
            $table->timestamps();

            // Seorang panitia (user) hanya bisa absen satu kali per rapat.
            $table->unique(['event_meeting_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_meeting_attendances');
        Schema::dropIfExists('event_meetings');
    }
};
