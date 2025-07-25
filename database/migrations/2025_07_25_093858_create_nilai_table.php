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
        Schema::create('nilai', function (Blueprint $table) {
            $table->bigIncrements('id_nilai');

            $table->unsignedBigInteger('detail_id');
            $table->unsignedBigInteger('jadwal_id');
            $table->unsignedBigInteger('tahun_akademik_id');

            $table->integer('nilai_sumatif')->nullable();
            $table->integer('nilai_pas')->nullable();
            $table->integer('nilai_pat')->nullable();

            $table->timestamps();

            // Foreign key constraints
            $table->foreign('detail_id')->references('id_detail')->on('detail')->onDelete('cascade');
            $table->foreign('jadwal_id')->references('id_jadwal')->on('jadwal')->onDelete('cascade');
            $table->foreign('tahun_akademik_id')->references('id_tahun_akademik')->on('tahun_akademik')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
