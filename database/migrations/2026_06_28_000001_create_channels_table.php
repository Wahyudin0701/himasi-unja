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
        Schema::create('channels', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // e.g. 'Pembina & Pimpinan', 'Divisi Ristek'
            $table->enum('type', ['pembina_pimpinan', 'dp_pimpinan', 'pimpinan_kadiv', 'kadiv_anggota']);
            $table->foreignId('division_id')->nullable()->constrained('divisions')->cascadeOnDelete();
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('channels');
    }
};
