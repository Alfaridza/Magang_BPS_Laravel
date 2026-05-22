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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_in')->nullable();
            $table->time('jam_out')->nullable();
            $table->string('lokasi_in')->nullable();  // format: "lat,lng"
            $table->string('lokasi_out')->nullable(); // format: "lat,lng"
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Terlambat'])->default('Hadir');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
