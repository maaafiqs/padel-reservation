<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $items = [
            'Raket Padel Babolat', 'Raket Padel Wilson', 'Raket Padel Head', 'Raket Padel Bullpadel', 'Raket Padel Adidas',
            'Raket Padel Nox', 'Raket Padel StarVie', 'Raket Padel Drop Shot', 'Bola Padel (Isi 3)', 'Bola Padel Pro (Isi 3)',
            'Grip Raket', 'Sepatu Padel (Sewa)', 'Handuk Kecil', 'Air Mineral 600ml', 'Isotonik 500ml'
        ];

        return [
            'name' => $this->faker->randomElement($items) . ' - ' . $this->faker->word(),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->randomElement([30000, 50000, 75000, 20000, 15000]),
            'stock' => $this->faker->numberBetween(5, 30),
            'image' => null,
        ];
    }
}
