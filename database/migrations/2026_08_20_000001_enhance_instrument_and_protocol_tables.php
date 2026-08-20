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
        Schema::table('butir_evaluasi', function (Blueprint $table) {
            $table->boolean('is_critical')->default(false)->after('urutan');
            $table->text('evidence_required')->nullable()->after('is_critical');
            $table->unsignedBigInteger('parent_item_id')->nullable()->after('evidence_required');
            $table->string('standar')->nullable()->after('parent_item_id');
            $table->string('parameter')->nullable()->after('standar');
        });

        Schema::table('jawaban_evaluasi', function (Blueprint $table) {
            $table->string('evidence_strength', 10)->nullable()->after('bukti'); // E0, E1, E2, E3, E4
            $table->foreignId('pic_user_id')->nullable()->after('evidence_strength')->constrained('users')->nullOnDelete();
        });

        Schema::table('anggota_kepk', function (Blueprint $table) {
            $table->string('peran_etik')->nullable()->after('jabatan'); // Ketua, Sekretaris, Anggota, Lay Person
            $table->string('keahlian')->nullable()->after('peran_etik');
            $table->string('afiliasi')->default('Internal')->after('keahlian');
            $table->string('gender', 20)->nullable()->after('afiliasi');
            $table->string('pendidikan')->nullable()->after('gender');
            $table->boolean('status_aktif')->default(true)->after('pendidikan');
        });

        Schema::table('list_protokol', function (Blueprint $table) {
            $table->string('review_type', 50)->default('full_board')->after('peneliti_utama'); // exempted, expedited, full_board
            $table->string('institusi_asal')->nullable()->after('review_type');
            $table->date('tanggal_review')->nullable()->after('tanggal_pengajuan');
            $table->string('nomor_surat_etik', 100)->nullable()->after('tanggal_review');
            $table->string('status_etik', 50)->nullable()->after('nomor_surat_etik');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('butir_evaluasi', function (Blueprint $table) {
            $table->dropColumn(['is_critical', 'evidence_required', 'parent_item_id', 'standar', 'parameter']);
        });

        Schema::table('jawaban_evaluasi', function (Blueprint $table) {
            $table->dropForeign(['pic_user_id']);
            $table->dropColumn(['evidence_strength', 'pic_user_id']);
        });

        Schema::table('anggota_kepk', function (Blueprint $table) {
            $table->dropColumn(['peran_etik', 'keahlian', 'afiliasi', 'gender', 'pendidikan', 'status_aktif']);
        });

        Schema::table('list_protokol', function (Blueprint $table) {
            $table->dropColumn(['review_type', 'institusi_asal', 'tanggal_review', 'nomor_surat_etik', 'status_etik']);
        });
    }
};
