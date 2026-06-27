<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('work_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_program_id')->nullable()->constrained('work_programs')->cascadeOnDelete();
            $table->foreignId('event_id')->nullable()->constrained('events')->cascadeOnDelete();
            $table->foreignId('division_id')->nullable()->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('event_division_id')->nullable()->constrained('event_divisions')->cascadeOnDelete();
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('attachments')->nullable();
            $table->text('revision_note')->nullable();
            
            $table->foreignId('assigned_to')->constrained('users')->cascadeOnDelete();
            $table->foreignId('assigned_by')->constrained('users')->cascadeOnDelete();
            
            $table->unsignedInteger('sprint_number')->nullable();
            $table->date('sprint_start_date')->nullable();
            $table->date('sprint_end_date')->nullable();
            
            $table->date('due_date')->nullable();
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->enum('status', ['todo', 'waiting', 'revisi', 'completed'])->default('todo');
            
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_tasks');
    }
};
