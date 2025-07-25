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
       Schema::create('santri', function (Blueprint $table) {
            $table->string('nis', 20)->primary();
            $table->string('nisn', 20)->nullable();
            $table->string('nama_santri', 100);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->text('alamat');
            $table->string('foto', 255)->nullable();
            $table->string('ayah', 100)->nullable();
            $table->string('ibu', 100)->nullable();
            $table->string('no_hp', 15);
            $table->unsignedBigInteger('wali_id');

            // Foreign key ke tabel users
            $table->foreign('wali_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('santri');
    }
};
