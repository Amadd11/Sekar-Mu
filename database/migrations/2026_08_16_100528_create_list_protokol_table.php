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
        Schema::create('list_protokol', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_pengajuan_id')->constrained('surat_pengajuan')->cascadeOnDelete();
            $table->string('nomor_protokol', 100);
            $table->string('judul');
            $table->string('peneliti_utama');
            $table->string('review_type', 50)->default('full_board'); // exempted, expedited, full_board
            $table->string('institusi_asal')->nullable();
            $table->date('tanggal_pengajuan')->nullable();
            $table->date('tanggal_review')->nullable();
            $table->string('nomor_surat_etik', 100)->nullable();
            $table->string('status_etik', 50)->nullable();
            $table->string('status', 50)->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('list_protokol');
    }
};
