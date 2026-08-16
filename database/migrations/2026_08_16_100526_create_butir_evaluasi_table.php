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
        Schema::create('butir_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelompok_evaluasi_id')->constrained('kelompok_evaluasi')->cascadeOnDelete();
            $table->text('pertanyaan');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('butir_evaluasi');
    }
};
