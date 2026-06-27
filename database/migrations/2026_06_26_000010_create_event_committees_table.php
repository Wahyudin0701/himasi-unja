<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_committees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained('events')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('committee_role_id')->constrained('committee_roles')->cascadeOnDelete();
            $table->foreignId('event_division_id')->nullable()->constrained('event_divisions')->cascadeOnDelete();
            $table->timestamps();

            // Constraint 1: Satu user hanya boleh punya SATU role di satu event
            // (mencegah user A menjadi Ketupel sekaligus CO di event yang sama)
            $table->unique(['event_id', 'user_id']);

            // Constraint 2: 1 event = 1 Ketupel ditegakkan di application layer via
            // KetupelDashboardController::eventAlreadyHasKetupel() sebelum assign role.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_committees');
    }
};
