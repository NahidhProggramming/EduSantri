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
        Schema::create('aktivitas_guru', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('guru_id');
            $table->string('deskripsi', 255);

            $table->timestamps();

            // Relasi ke tabel guru
            $table->foreign('guru_id')->references('id_guru')->on('guru')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aktivitas_guru');
    }
};
