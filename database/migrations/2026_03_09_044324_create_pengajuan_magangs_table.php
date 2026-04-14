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
        Schema::create('pengajuan_magangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('status_peserta', ['Mahasiswa', 'Fresh graduated', 'Siswa']);
            $table->enum('jenis_magang', ['Magang Wajib/PKL', 'Magang Mandiri']);
            $table->string('nim_nisn');
            $table->enum('jenjang_pendidikan', ['SMK/SMA', 'Diploma', 'D4/S1']);
            $table->string('nama_sekolah');
            $table->string('jurusan');
            $table->date('periode_mulai');
            $table->date('periode_selesai');
            $table->text('tema_magang')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_magangs');
    }
};
