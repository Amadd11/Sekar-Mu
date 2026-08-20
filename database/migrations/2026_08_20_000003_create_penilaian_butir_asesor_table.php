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
        Schema::create('penilaian_butir_asesor', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_pengajuan_id')->constrained('surat_pengajuan')->cascadeOnDelete();
            $table->foreignId('penilai_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('butir_evaluasi_id')->constrained('butir_evaluasi')->cascadeOnDelete();
            $table->string('skor', 10)->nullable(); // A, B, C, D
            $table->text('catatan')->nullable();
            $table->text('temuan')->nullable();
            $table->text('rekomendasi')->nullable();
            $table->timestamps();

            $table->unique(['surat_pengajuan_id', 'penilai_id', 'butir_evaluasi_id'], 'penilaian_butir_unik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_butir_asesor');
    }
};
