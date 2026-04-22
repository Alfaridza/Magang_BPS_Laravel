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
        \App\Models\Admin::updateOrCreate(
            ['email' => 'admin@bps.go.id'],
            [
                'name' => 'Administrator BPS',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'no_hp' => '081234567890',
            ]
        );
    }
}
