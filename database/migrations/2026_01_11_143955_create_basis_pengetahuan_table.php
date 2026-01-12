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
        Schema::create('basis_pengetahuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penyakit');
            $table->unsignedBigInteger('id_gejala');
            $table->decimal('bobot', 3, 2)->default(0.00);
            $table->timestamps();

            $table->foreign('id_penyakit')->references('id')->on('penyakit')->onDelete('cascade');
            $table->foreign('id_gejala')->references('id')->on('gejala')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basis_pengetahuan');
    }
};
