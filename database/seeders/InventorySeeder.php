<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inventories are curated with unique item codes in DummyDataSeeder
        if (\App\Models\Inventory::count() === 0) {
            $this->call(DummyDataSeeder::class);
        }
    }
}
