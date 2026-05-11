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
            $table->string('kelas')->nullable()->after('jurusan');
            $table->string('fakultas')->nullable()->after('kelas');
            $table->string('semester')->nullable()->after('fakultas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_magangs', function (Blueprint $table) {
            $table->dropColumn(['kelas', 'fakultas', 'semester']);
        });
    }
};
