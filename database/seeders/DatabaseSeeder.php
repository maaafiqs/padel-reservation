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
            ['name' => 'Budi Santoso', 'email' => 'budi.santoso@gmail.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Siti Aminah', 'email' => 'siti.aminah@gmail.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Kevin Wijaya', 'email' => 'kevin.wijaya@gmail.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Amanda Putri', 'email' => 'amanda.putri@gmail.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Reza Rahadian', 'email' => 'reza.rahadian@gmail.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Clara Tan', 'email' => 'clara.tan@gmail.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Fajar Alfian', 'email' => 'fajar.alfian@gmail.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Greysia Polii', 'email' => 'greysia.polii@gmail.com', 'password' => Hash::make('password'), 'role' => 'user'],
            ['name' => 'Marcus Fernaldi Gideon', 'email' => 'marcus.gideon@gmail.com', 'password' => Hash::make('password'), 'role' => 'user'],
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
