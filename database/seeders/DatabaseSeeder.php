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
        // Admin & Staff Accounts
        $admins = [
            [
                'name' => 'Admin Padel Arena',
                'email' => 'admin@maaafiqspadel.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
            [
                'name' => 'Staff Operator & Kasir',
                'email' => 'staff@maaafiqspadel.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ],
        ];
        foreach ($admins as $admin) {
            User::updateOrCreate(['email' => $admin['email']], $admin);
        }

        // Regular Member / User Accounts (Minimal 10 accounts)
        $users = [
            ['name' => 'Pengguna Demo', 'email' => 'user@maaafiqspadel.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@example.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Siti Aminah', 'email' => 'siti.aminah@example.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Kevin Wijaya', 'email' => 'kevin.wijaya@example.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Amanda Putri', 'email' => 'amanda.putri@example.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Reza Rahadian', 'email' => 'reza.rahadian@example.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Clara Tan', 'email' => 'clara.tan@example.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Fajar Alfian', 'email' => 'fajar.alfian@example.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Greysia Polii', 'email' => 'greysia.polii@example.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Marcus Fernaldi Gideon', 'email' => 'marcus.gideon@example.com', 'password' => Hash::make('password'), 'role' => 'user'],
        ];
        foreach ($users as $u) {
            User::updateOrCreate(['email' => $u['email']], $u);
        }

        $this->call([
            FacilitySeeder::class,
            DummyDataSeeder::class,
            ReservationSeeder::class,
        ]);
    }
}
