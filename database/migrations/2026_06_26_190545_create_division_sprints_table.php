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
        Schema::create('division_sprints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_division_id')->constrained('event_divisions')->cascadeOnDelete();
            $table->unsignedInteger('sprint_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->timestamps();

            $table->unique(['event_division_id', 'sprint_number'], 'evt_div_sprint_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('division_sprints');
    }
};
