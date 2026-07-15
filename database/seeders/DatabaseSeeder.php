<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin User
        User::create([
            'name' => 'Admin Padel',
            'email' => 'admin@maaafiqspadel.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Regular User
        User::create([
            'name' => 'Pengguna Biasa',
            'email' => 'user@maaafiqspadel.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        $this->call([
            DummyDataSeeder::class,
            InventorySeeder::class,
        ]);
    }
}
