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
            $table->id(); // Menambahkan kolom ID sebagai primary key
            $table->integer('id_status')->nullable();
            $table->integer('profil_tes_id')->nullable();
            $table->integer('id_siswa')->nullable();
            $table->integer('soal_bank_paket_id')->nullable();
            $table->string('nama', 500)->nullable();
            $table->string('nisn', 500)->nullable();
            $table->char('jk', 1)->nullable();
            $table->decimal('skor', 10, 0)->nullable();
            $table->integer('soal_benar')->nullable();
            $table->string('nama_pelajaran', 500)->nullable();
            $table->integer('pelajaran_id')->nullable();
            $table->integer('materi_uji_id')->nullable();
            $table->integer('sesi')->nullable();
            $table->integer('id_pelaksanaan')->nullable();
            $table->string('nama_sekolah', 500)->nullable();
            $table->integer('total_soal')->nullable();
            $table->integer('urutan')->nullable();
            $table->timestamps(); // Menambahkan kolom timestamps untuk created_at dan updated_at
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
