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
        Schema::create('jawaban_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_pengajuan_id')->constrained('surat_pengajuan')->cascadeOnDelete();
            $table->foreignId('butir_evaluasi_id')->constrained('butir_evaluasi')->cascadeOnDelete();
            $table->string('skor', 10)->nullable();
            $table->string('evidence_strength', 10)->nullable(); // E0, E1, E2, E3, E4
            $table->foreignId('pic_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('catatan')->nullable();
            $table->text('bukti')->nullable();
            $table->json('file_attachments')->nullable();
            $table->timestamps();

            $table->unique(['surat_pengajuan_id', 'butir_evaluasi_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_evaluasi');
    }
};
