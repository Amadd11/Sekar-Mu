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
        Schema::create('penilaian_etik', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_pengajuan_id')->constrained('surat_pengajuan')->cascadeOnDelete();
            $table->foreignId('penilai_id')->constrained('users')->cascadeOnDelete();
            $table->string('rekomendasi', 50);
            $table->text('catatan')->nullable();
            $table->date('tanggal_keputusan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian_etik');
    }
};
