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
        Schema::table('list_protokol', function (Blueprint $table) {
            $table->string('dokumen_path')->nullable()->after('status');
            $table->string('dokumen_nama')->nullable()->after('dokumen_path');
            $table->unsignedBigInteger('dokumen_ukuran')->nullable()->after('dokumen_nama');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('list_protokol', function (Blueprint $table) {
            $table->dropColumn(['dokumen_path', 'dokumen_nama', 'dokumen_ukuran']);
        });
    }
};
