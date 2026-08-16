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
        Schema::create('kelompok_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bagian_evaluasi_id')->constrained('bagian_evaluasi')->cascadeOnDelete();
            $table->string('nama');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelompok_evaluasi');
    }
};
