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
        Schema::create('kendalas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_in')->nullable();
            $table->time('jam_out')->nullable();
            $table->enum('jenis_kendala', ['Lokasi Tidak Terdeteksi', 'Sistem sedang Error', 'Lainnya']);
            $table->text('keterangan');
            $table->tinyInteger('status_approve')->default(0); // 0: Pending, 1: Approved, 2: Rejected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kendalas');
    }
};
