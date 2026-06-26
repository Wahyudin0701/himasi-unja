<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('period_id')->constrained('periods')->cascadeOnDelete();
            $table->string('name');                         // "BPH", "Humas", "Ristek"
            $table->string('slug');
            $table->string('singkatan')->nullable();
            $table->string('icon')->nullable();             // emoji
            $table->string('color')->nullable();            // bg-indigo-50
            $table->string('text_color')->nullable();
            $table->text('description')->nullable();
            $table->enum('type', ['bph', 'divisi'])->default('divisi');
            $table->integer('base_points')->default(100);
            $table->timestamps();

            $table->unique(['period_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
