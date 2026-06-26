<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('committee_roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');         // "Ketupel", "Sekpel", "CO", "Pendamping Gugus"
            $table->string('slug')->unique();
            $table->integer('level');       // hierarchy order
            $table->enum('scope', ['inti', 'divisi']); // inti = tim inti, divisi = per divisi
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('committee_roles');
    }
};
