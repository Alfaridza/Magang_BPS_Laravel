<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Rename columns:
     *   waktu_masuk      -> jam_in
     *   waktu_pulang     -> jam_out
     *   latitude_masuk   -> (digabung ke lokasi_in)
     *   longitude_masuk  -> (digabung ke lokasi_in)
     *   latitude_pulang  -> (digabung ke lokasi_out)
     *   longitude_pulang -> (digabung ke lokasi_out)
     */
    public function up(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            // Rename waktu_masuk -> jam_in
            $table->renameColumn('waktu_masuk', 'jam_in');

            // Rename waktu_pulang -> jam_out
            $table->renameColumn('waktu_pulang', 'jam_out');
        });

        Schema::table('presensis', function (Blueprint $table) {
            // Tambah kolom lokasi_in & lokasi_out (format: "lat,lng")
            $table->string('lokasi_in')->nullable()->after('jam_out');
            $table->string('lokasi_out')->nullable()->after('lokasi_in');
        });

        // Gabungkan data latitude & longitude yang sudah ada ke lokasi_in / lokasi_out
        DB::table('presensis')->chunkById(100, function ($rows) {
            foreach ($rows as $row) {
                $lokasi_in  = null;
                $lokasi_out = null;

                if (!empty($row->latitude_masuk) && !empty($row->longitude_masuk)) {
                    $lokasi_in = $row->latitude_masuk . ',' . $row->longitude_masuk;
                }
                if (!empty($row->latitude_pulang) && !empty($row->longitude_pulang)) {
                    $lokasi_out = $row->latitude_pulang . ',' . $row->longitude_pulang;
                }

                DB::table('presensis')->where('id', $row->id)->update([
                    'lokasi_in'  => $lokasi_in,
                    'lokasi_out' => $lokasi_out,
                ]);
            }
        });

        Schema::table('presensis', function (Blueprint $table) {
            // Hapus kolom lama latitude & longitude
            $table->dropColumn(['latitude_masuk', 'longitude_masuk', 'latitude_pulang', 'longitude_pulang']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('presensis', function (Blueprint $table) {
            // Kembalikan kolom latitude & longitude
            $table->decimal('latitude_masuk', 10, 7)->nullable()->after('jam_out');
            $table->decimal('longitude_masuk', 10, 7)->nullable()->after('latitude_masuk');
            $table->decimal('latitude_pulang', 10, 7)->nullable()->after('longitude_masuk');
            $table->decimal('longitude_pulang', 10, 7)->nullable()->after('latitude_pulang');
        });

        Schema::table('presensis', function (Blueprint $table) {
            $table->dropColumn(['lokasi_in', 'lokasi_out']);
        });

        Schema::table('presensis', function (Blueprint $table) {
            $table->renameColumn('jam_in', 'waktu_masuk');
            $table->renameColumn('jam_out', 'waktu_pulang');
        });
    }
};
