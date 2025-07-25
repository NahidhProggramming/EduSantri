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
        Schema::create('detail', function (Blueprint $table) {
            $table->bigIncrements('id_detail');

            $table->unsignedBigInteger('kelas_id');
            $table->unsignedBigInteger('tahun_akademik_id');
            $table->string('santri_id', 20);
            $table->unsignedBigInteger('sekolah_id');

            $table->timestamps();

            // Relasi ke tabel-tabel lain
            $table->foreign('kelas_id')->references('id_kelas')->on('kelas')->onDelete('cascade');
            $table->foreign('tahun_akademik_id')->references('id_tahun_akademik')->on('tahun_akademik')->onDelete('cascade');
            $table->foreign('santri_id')->references('nis')->on('santri')->onDelete('cascade');
            $table->foreign('sekolah_id')->references('id_sekolah')->on('sekolah')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail');
    }
};
