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
        Schema::create('inventory_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_id')->constrained('inventories')->cascadeOnDelete();
            $table->foreignId('borrower_id')->constrained('users')->cascadeOnDelete();
            $table->integer('quantity');
            $table->enum('status', ['Diajukan', 'Dipinjam', 'Dikembalikan'])->default('Diajukan');
            $table->string('photo_before_path')->nullable();
            $table->string('photo_after_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_loans');
    }
};
