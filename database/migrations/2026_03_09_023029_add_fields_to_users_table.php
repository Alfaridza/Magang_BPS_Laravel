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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('password');
            $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
            $table->string('no_hp')->nullable()->after('tanggal_lahir');
            $table->text('alamat')->nullable()->after('no_hp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['jenis_kelamin', 'tanggal_lahir', 'no_hp', 'alamat']);
        });
    }
};
