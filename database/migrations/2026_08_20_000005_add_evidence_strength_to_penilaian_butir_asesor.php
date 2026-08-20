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
        Schema::table('penilaian_butir_asesor', function (Blueprint $table) {
            $table->string('evidence_strength', 10)->nullable()->after('skor'); // E0, E1, E2, E3, E4
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('penilaian_butir_asesor', function (Blueprint $table) {
            $table->dropColumn('evidence_strength');
        });
    }
};
