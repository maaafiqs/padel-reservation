<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Facility;

class FacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'name' => 'Ruang Loker & Ganti Atlet',
                'description' => 'Ruang ganti ber-AC yang bersih dan terpisah untuk pria dan wanita, dilengkapi dengan loker berkeamanan tinggi.',
                'icon' => 'fa-solid fa-lock',
            ],
            [
                'name' => 'Kamar Mandi Shower Air Panas',
                'description' => 'Fasilitas shower air hangat bertekanan tinggi lengkap dengan sabun, sampo, dan handuk sewa untuk kesegaran setelah bertanding.',
                'icon' => 'fa-solid fa-shower',
            ],
            [
                'name' => 'Free High-Speed Wi-Fi',
                'description' => 'Akses internet nirkabel berkecepatan hingga 200 Mbps yang mencakup seluruh arena, kafe, dan ruang istirahat.',
                'icon' => 'fa-solid fa-wifi',
            ],
            [
                'name' => 'Athlete Lounge & Kafe',
                'description' => 'Lounge berpendingin udara yang menyajikan kopi spesialti, jus segar, makanan sehat, dan minuman elektrolit dingin.',
                'icon' => 'fa-solid fa-mug-hot',
            ],
            [
                'name' => 'Pro Shop & Rental Gear',
                'description' => 'Toko perlengkapan resmi padel yang menjual dan menyewakan raket, tas, pakaian olahraga, bola, serta layanan penggantian overgrip.',
                'icon' => 'fa-solid fa-shop',
            ],
            [
                'name' => 'Musholla & Tempat Wudhu',
                'description' => 'Musholla yang nyaman, tenang, dan bersih dengan sajadah wangi dan fasilitas tempat wudhu terpisah.',
                'icon' => 'fa-solid fa-mosque',
            ],
            [
                'name' => 'Area Parkir Mobil & Motor Luas',
                'description' => 'Tempat parkir berkapasitas lebih dari 80 mobil dan 100 sepeda motor dengan pengawasan kamera CCTV dan keamanan 24 jam.',
                'icon' => 'fa-solid fa-square-parking',
            ],
            [
                'name' => 'Tribun Penonton Beratap',
                'description' => 'Tribun nyaman untuk penonton dan suporter turnamen dengan pandangan jelas tanpa terhalang ke seluruh lapangan pertandingan.',
                'icon' => 'fa-solid fa-users',
            ],
            [
                'name' => 'Pencahayaan LED FIP Turnamen',
                'description' => 'Sistem lampu sorot LED 800+ Lux anti-silau berstandar Federasi Padel Internasional untuk pertandingan malam hari tanpa bayangan.',
                'icon' => 'fa-solid fa-lightbulb',
            ],
            [
                'name' => 'Stasiun Pengisian Daya EV',
                'description' => 'Fasilitas pengisian daya kendaraan listrik (Electric Vehicle) tipe AC Charging 22 kW untuk kenyamanan pengunjung.',
                'icon' => 'fa-solid fa-charging-station',
            ],
            [
                'name' => 'Pos Medis & P3K Darurat',
                'description' => 'Peralatan pertolongan pertama pada kecelakaan (P3K), kompres es instan untuk sprain sendi, dan staf terlatih untuk penanganan cedera olahraga.',
                'icon' => 'fa-solid fa-briefcase-medical',
            ],
        ];

        foreach ($facilities as $facility) {
            Facility::updateOrCreate(
                ['name' => $facility['name']],
                $facility
            );
        }
    }
}
