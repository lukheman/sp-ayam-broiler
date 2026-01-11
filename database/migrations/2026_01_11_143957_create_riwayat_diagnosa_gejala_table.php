<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('riwayat_diagnosa_gejala', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_riwayat_diagnosa');
            $table->unsignedBigInteger('id_gejala');

            $table->foreign('id_riwayat_diagnosa')->references('id')->on('riwayat_diagnosa')->onDelete('cascade');
            $table->foreign('id_gejala')->references('id')->on('gejala')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_diagnosa_gejala');
    }
};
