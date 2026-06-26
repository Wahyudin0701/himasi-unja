<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');   // e.g. "Ketua Himpunan", "Kadiv", "Anggota"
            $table->string('slug')->unique();
            $table->integer('level'); // hierarchy order (1=kahim, 2=wakahim, ...)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_positions');
    }
};
