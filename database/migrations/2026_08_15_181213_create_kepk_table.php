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
        Schema::create('kepk', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institusi_id')->constrained('institusi')->cascadeOnDelete();
            $table->string('code', 50)->unique();
            $table->string('name');
            $table->string('status', 50)->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kepk');
    }
};
