<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('division_id')->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('org_position_id')->constrained('org_positions')->cascadeOnDelete();
            $table->foreignId('sub_division_id')->nullable()->constrained('sub_divisions')->nullOnDelete();
            $table->string('position_title')->nullable(); // custom: "Ketua Bidang Riset"
            $table->date('joined_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'division_id']); // satu user hanya satu divisi per periode
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
