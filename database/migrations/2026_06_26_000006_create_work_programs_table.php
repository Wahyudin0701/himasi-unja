<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('sub_division_id')->nullable()->constrained('sub_divisions')->nullOnDelete();
            $table->string('name');
            $table->foreignId('pic_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('type', ['event', 'internal', 'kolaborasi'])->default('internal');
            $table->text('description')->nullable();
            $table->text('objective')->nullable();
            $table->text('target_audience')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->decimal('budget_plan', 15, 2)->nullable();
            $table->enum('status', ['planning', 'ongoing', 'completed', 'cancelled'])->default('planning');
            $table->integer('progress_percentage')->default(0);
            $table->text('cancellation_reason')->nullable();
            $table->timestamps();
        });

        Schema::create('work_program_partner_divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_program_id')->constrained('work_programs')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('work_program_collaborators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_program_id')->constrained('work_programs')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_program_collaborators');
        Schema::dropIfExists('work_program_partner_divisions');
        Schema::dropIfExists('work_programs');
    }
};
