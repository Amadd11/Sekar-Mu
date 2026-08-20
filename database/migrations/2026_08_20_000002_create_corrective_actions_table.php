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
        Schema::create('corrective_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surat_pengajuan_id')->constrained('surat_pengajuan')->cascadeOnDelete();
            $table->foreignId('butir_evaluasi_id')->nullable()->constrained('butir_evaluasi')->nullOnDelete();
            $table->text('finding');
            $table->text('risk')->nullable();
            $table->text('action');
            $table->string('pic_name')->nullable();
            $table->string('priority', 20)->default('MEDIUM'); // HIGH, MEDIUM, LOW
            $table->date('deadline')->nullable();
            $table->string('status', 30)->default('OPEN'); // OPEN, IN_PROGRESS, SUBMITTED, VERIFIED, CLOSED
            $table->string('evidence_path')->nullable();
            $table->text('verification_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('corrective_actions');
    }
};
