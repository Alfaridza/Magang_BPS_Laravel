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
        Schema::table('konfigurasi_jam_kerja', function (Blueprint $table) {
            $table->string('nama')->after('id');
            $table->time('jam_masuk_toleransi')->after('jam_masuk');
            $table->boolean('is_wfa')->default(0)->after('jam_pulang');
            $table->date('tanggal_mulai')->nullable()->after('is_wfa');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->integer('radius_meter')->default(50)->after('status');
            $table->dropColumn('toleransi_menit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('konfigurasi_jam_kerja', function (Blueprint $table) {
            $table->dropColumn(['nama', 'jam_masuk_toleransi', 'is_wfa', 'tanggal_mulai', 'tanggal_selesai', 'status', 'radius_meter']);
            $table->integer('toleransi_menit')->default(0)->after('jam_pulang');
        });
    }
};
