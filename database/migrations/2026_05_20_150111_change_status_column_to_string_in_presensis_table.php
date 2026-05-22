<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE presensis MODIFY COLUMN status VARCHAR(255) DEFAULT 'Hadir'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE presensis MODIFY COLUMN status ENUM('Hadir', 'Izin', 'Sakit', 'Terlambat', 'Izin Setengah Hari') DEFAULT 'Hadir'");
    }
};
