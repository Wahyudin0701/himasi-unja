<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('event_letters', function (Blueprint $table) {
            // Kolom pengaju (Sekpel atau Ketupel yang mengajukan)
            $table->foreignId('submitted_by')->nullable()->after('event_id')->constrained('users')->onDelete('set null');

            // File lampiran draft surat dari Sekpel
            $table->string('file_path')->nullable()->after('submitted_by');

            // Status pengajuan: pending = menunggu review, revision = perlu direvisi, approved = disetujui
            $table->enum('status', ['pending', 'revision', 'approved'])->default('pending')->after('file_path');

            // Reviewer dari pihak Sekretaris HIMA
            $table->foreignId('reviewed_by')->nullable()->after('status')->constrained('users')->onDelete('set null');

            // Catatan revisi dari Sekretaris HIMA
            $table->text('review_notes')->nullable()->after('reviewed_by');

            // Waktu persetujuan
            $table->timestamp('approved_at')->nullable()->after('review_notes');

            // Nomor surat & tanggal surat dijadikan nullable (diisi saat approved)
            $table->string('letter_number')->nullable()->change();
            $table->date('letter_date')->nullable()->change();

            // Tipe surat tidak lagi wajib saat pengajuan awal (bisa diisi saat approved)
            $table->enum('type', ['masuk', 'keluar'])->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('event_letters', function (Blueprint $table) {
            $table->dropForeign(['submitted_by']);
            $table->dropForeign(['reviewed_by']);
            $table->dropColumn(['submitted_by', 'file_path', 'status', 'reviewed_by', 'review_notes', 'approved_at']);
            $table->string('letter_number')->nullable(false)->change();
            $table->date('letter_date')->nullable(false)->change();
            $table->enum('type', ['masuk', 'keluar'])->nullable(false)->change();
        });
    }
};
