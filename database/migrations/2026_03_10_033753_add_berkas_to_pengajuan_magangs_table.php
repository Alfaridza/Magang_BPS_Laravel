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
            $table->string('surat_pengantar')->nullable()->after('tema_magang');
            $table->string('pas_foto')->nullable()->after('surat_pengantar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_magangs', function (Blueprint $table) {
            $table->dropColumn(['surat_pengantar', 'pas_foto']);
        });
    }
};
