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
        Schema::table('pengajuan_magangs', function (Blueprint $table) {
            $table->enum('status_pengajuan', ['Menunggu', 'Diterima', 'Ditolak'])->default('Menunggu')->after('tema_magang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_magangs', function (Blueprint $table) {
            $table->dropColumn('status_pengajuan');
        });
    }
};
