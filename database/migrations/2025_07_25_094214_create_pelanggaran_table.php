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
        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->bigIncrements('id_pelanggaran');

            $table->string('santri_nis', 20);
            $table->unsignedBigInteger('jenis_pelanggaran_id')->nullable();
            $table->text('deskripsi')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('file_surat', 255)->nullable();
            $table->enum('verifikasi_surat', ['Belum Diverifikasi', 'Terverifikasi', 'Ditolak'])->default('Belum Diverifikasi');

            $table->timestamps();
            
            // Foreign key constraints
            $table->foreign('santri_nis')->references('nis')->on('santri')->onDelete('cascade');
            $table->foreign('jenis_pelanggaran_id')->references('id_jenis')->on('jenis_pelanggaran')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggaran');
    }
};
