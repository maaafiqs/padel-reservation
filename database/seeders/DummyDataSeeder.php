<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Court;
use App\Models\Coach;
use App\Models\Inventory;
use App\Models\Announcement;
use App\Models\Discount;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    public function run()
    {
        // 5 Courts
        $courts = [
            ['name' => 'Indoor Pro Court A', 'description' => 'Lapangan indoor profesional dengan lantai khusus padel kualitas turnamen.', 'type' => 'Indoor', 'price_per_hour' => 250000, 'status' => 'available'],
            ['name' => 'Indoor Pro Court B', 'description' => 'Lapangan indoor reguler untuk latihan maupun bertanding.', 'type' => 'Indoor', 'price_per_hour' => 200000, 'status' => 'available'],
            ['name' => 'Outdoor Skyline Court', 'description' => 'Lapangan outdoor dengan pemandangan kota. Seru dimainkan sore hari.', 'type' => 'Outdoor', 'price_per_hour' => 150000, 'status' => 'available'],
            ['name' => 'Outdoor Sunset Court', 'description' => 'Lapangan padel outdoor standar.', 'type' => 'Outdoor', 'price_per_hour' => 150000, 'status' => 'available'],
            ['name' => 'VIP Glass Court', 'description' => 'Lapangan dengan dinding full kaca khusus untuk VIP member.', 'type' => 'Indoor', 'price_per_hour' => 350000, 'status' => 'maintenance'],
        ];
        foreach ($courts as $court) {
            Court::updateOrCreate(['name' => $court['name']], $court);
        }

        // 5 Coaches
        $coaches = [
            ['name' => 'Coach Bima', 'bio' => 'Mantan atlet nasional tenis yang banting setir menjadi pro padel.', 'price_per_hour' => 150000, 'capacity' => 4, 'phone' => '081234567801', 'is_available' => true],
            ['name' => 'Coach Sarah', 'bio' => 'Spesialis mengajar pemula dan anak-anak.', 'price_per_hour' => 120000, 'capacity' => 6, 'phone' => '081234567802', 'is_available' => true],
            ['name' => 'Coach Anton', 'bio' => 'Pelatih taktik dan strategi ganda padel.', 'price_per_hour' => 150000, 'capacity' => 4, 'phone' => '081234567803', 'is_available' => true],
            ['name' => 'Coach Dita', 'bio' => 'Pelatih fisik dan stamina khusus padel.', 'price_per_hour' => 100000, 'capacity' => 10, 'phone' => '081234567804', 'is_available' => true],
            ['name' => 'Coach Ricky (Pro)', 'bio' => 'Pelatih level advanced bersertifikasi internasional.', 'price_per_hour' => 250000, 'capacity' => 2, 'phone' => '081234567805', 'is_available' => false],
        ];
        foreach ($coaches as $coach) {
            Coach::updateOrCreate(['name' => $coach['name']], $coach);
        }

        // 5 Inventories
        $inventories = [
            ['item_code' => 'INV-001', 'name' => 'Raket Padel Babolat (Sewa)', 'description' => 'Raket padel merek Babolat untuk disewa per sesi.', 'price' => 50000, 'stock' => 10, 'is_consumable' => false],
            ['item_code' => 'INV-002', 'name' => 'Raket Padel Head (Sewa)', 'description' => 'Raket padel merek Head untuk latihan dan turnamen.', 'price' => 45000, 'stock' => 15, 'is_consumable' => false],
            ['item_code' => 'INV-003', 'name' => 'Bola Padel (Slop)', 'description' => 'Bola padel isi 3 baru berstandar turnamen internasional.', 'price' => 120000, 'stock' => 50, 'is_consumable' => true],
            ['item_code' => 'INV-004', 'name' => 'Handgrip Raket', 'description' => 'Grip tambahan anti-slip untuk kenyamanan bermain.', 'price' => 30000, 'stock' => 100, 'is_consumable' => true],
            ['item_code' => 'INV-005', 'name' => 'Minuman Isotonik', 'description' => 'Minuman dingin penambah ion tubuh dan hidrasi.', 'price' => 15000, 'stock' => 200, 'is_consumable' => true],
        ];
        foreach ($inventories as $inv) {
            Inventory::updateOrCreate(['item_code' => $inv['item_code']], $inv);
        }

        // 5 Announcements
        $announcements = [
            ['title' => 'Promo Grand Opening', 'content' => 'Selamat datang di Maaafiqs Padel! Nikmati diskon hingga 50% untuk bulan pertama operasional kami. Yuk segera booking lapanganmu!', 'is_active' => true],
            ['title' => 'Turnamen Padel Amatir 2026', 'content' => 'Daftarkan tim ganda kamu untuk turnamen Padel Amatir akhir tahun ini. Hadiah jutaan rupiah menanti!', 'is_active' => true],
            ['title' => 'Perawatan Lapangan Rutin', 'content' => 'Setiap hari Senin jam 08:00 - 12:00, lapangan VIP akan ditutup untuk perawatan rutin.', 'is_active' => true],
            ['title' => 'Aturan Sepatu Padel', 'content' => 'Demi menjaga kualitas lapangan, semua pemain diwajibkan menggunakan sepatu olahraga bersol karet datar atau sepatu khusus padel/tenis.', 'is_active' => true],
            ['title' => 'Coach Baru Bergabung', 'content' => 'Sambut Coach Sarah yang siap membantu kalian dari tingkat dasar. Booking sekarang!', 'is_active' => false],
        ];
        foreach ($announcements as $ann) {
            Announcement::updateOrCreate(['title' => $ann['title']], $ann);
        }

        // 5 Discounts
        $discounts = [
            ['code' => 'WELCOME50', 'type' => 'percentage', 'percentage' => 50, 'nominal_amount' => null, 'valid_until' => Carbon::now()->addDays(30), 'is_active' => true],
            ['code' => 'WEEKEND20', 'type' => 'percentage', 'percentage' => 20, 'nominal_amount' => null, 'valid_until' => Carbon::now()->addDays(60), 'is_active' => true],
            ['code' => 'POTONGAN50RB', 'type' => 'nominal', 'percentage' => null, 'nominal_amount' => 50000, 'valid_until' => Carbon::now()->addDays(45), 'is_active' => true],
            ['code' => 'STUDENT10', 'type' => 'percentage', 'percentage' => 10, 'nominal_amount' => null, 'valid_until' => null, 'is_active' => true],
            ['code' => 'EXPIRED5', 'type' => 'percentage', 'percentage' => 5, 'nominal_amount' => null, 'valid_until' => Carbon::now()->subDays(5), 'is_active' => false],
        ];
        foreach ($discounts as $disc) {
            Discount::updateOrCreate(['code' => $disc['code']], $disc);
        }
    }
}
