<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::updateOrCreate(
            ['email' => 'admin@bps.go.id'],
            [
                'name' => 'Administrator BPS',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '1990-01-01',
                'no_hp' => '081234567890',
                'alamat' => 'Kantor BPS Provinsi Banten',
                'role' => 'admin',
            ]
        );
    }
}
