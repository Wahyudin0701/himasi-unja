<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proker_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_program_id')->constrained('work_programs')->cascadeOnDelete();
            $table->foreignId('author_id')->constrained('users')->cascadeOnDelete();
            $table->text('content');
            $table->integer('progress_update'); // Persentase yang diajukan
            $table->string('attachment')->nullable();
            $table->enum('status', ['pending', 'approved', 'revised'])->default('pending');
            $table->text('feedback')->nullable(); // Catatan dari Kadiv
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proker_logs');
    }
};
