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
        // 12 Courts (Minimal 10 items)
        $courts = [
            ['name' => 'Center Court Pro (Arena 1)', 'description' => 'Lapangan utama berstandar World Padel Tour dengan lantai karpet Mondo Supercourt XN dan dinding full tempered glass 12mm.', 'type' => 'Indoor', 'price_per_hour' => 280000, 'status' => 'available'],
            ['name' => 'Indoor Panoramic Court A (Arena 2)', 'description' => 'Lapangan indoor ber-AC dengan konstruksi tanpa tiang sudut untuk visibilitas 360 derajat terbaik.', 'type' => 'Indoor', 'price_per_hour' => 250000, 'status' => 'available'],
            ['name' => 'Indoor Panoramic Court B (Arena 3)', 'description' => 'Lapangan indoor berstandar turnamen, dilengkapi sistem peredam akustik dan ventilasi modern.', 'type' => 'Indoor', 'price_per_hour' => 250000, 'status' => 'available'],
            ['name' => 'Sunset Skyline Court 1 (Arena 4)', 'description' => 'Lapangan outdoor dengan pemandangan cakrawala kota yang menawan saat sore menjelang malam hari.', 'type' => 'Outdoor', 'price_per_hour' => 180000, 'status' => 'available'],
            ['name' => 'Sunset Skyline Court 2 (Arena 5)', 'description' => 'Lapangan outdoor dengan pelindung angin (windbreak mesh) dan rumput sintetis monofilamen premium.', 'type' => 'Outdoor', 'price_per_hour' => 180000, 'status' => 'available'],
            ['name' => 'Rooftop Arena Alpha (Arena 6)', 'description' => 'Sensasi bermain padel di rooftop lantai 5 dengan sirkulasi udara sejuk dan lampu LED anti-glare.', 'type' => 'Outdoor', 'price_per_hour' => 200000, 'status' => 'available'],
            ['name' => 'Rooftop Arena Beta (Arena 7)', 'description' => 'Lapangan rooftop eksklusif dengan area lounge santai di pinggir lapangan dan spot foto ikonik.', 'type' => 'Outdoor', 'price_per_hour' => 200000, 'status' => 'available'],
            ['name' => 'Grand Slam Court (Arena 8)', 'description' => 'Lapangan indoor dengan ketinggian ceiling 12 meter bebas hambatan untuk pukulan lob dan smash tinggi.', 'type' => 'Indoor', 'price_per_hour' => 260000, 'status' => 'available'],
            ['name' => 'Family & Beginner Court (Arena 9)', 'description' => 'Didesain khusus untuk pemula dan keluarga dengan kecepatan pantul bola yang ramah latihan.', 'type' => 'Indoor', 'price_per_hour' => 160000, 'status' => 'available'],
            ['name' => 'VIP Glass Pavilion (Arena 10)', 'description' => 'Lapangan privat VIP ber-AC lengkap dengan akses ruang ganti pribadi, lounge eksklusif, dan smart scoreboard.', 'type' => 'Indoor', 'price_per_hour' => 350000, 'status' => 'available'],
            ['name' => 'Championship Tour Court (Arena 11)', 'description' => 'Lapangan outdoor kompetisi resmi dengan tribun penonton dan kursi wasit turnamen profesional.', 'type' => 'Outdoor', 'price_per_hour' => 220000, 'status' => 'available'],
            ['name' => 'Training & Drill Court (Arena 12)', 'description' => 'Lapangan khusus sesi latihan drill teknik, dilengkapi mesin pelontar bola otomatis (dalam pemeliharaan berkala).', 'type' => 'Indoor', 'price_per_hour' => 190000, 'status' => 'maintenance'],
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

        // 14 Inventories (Minimal 10 items)
        $inventories = [
            ['item_code' => 'INV-001', 'name' => 'Raket Padel Babolat Technical Viper (Sewa)', 'description' => 'Raket padel profesional kelas atas merek Babolat untuk disewa per sesi (fokus power & finishing eksplosif).', 'price' => 50000, 'stock' => 15, 'is_consumable' => false],
            ['item_code' => 'INV-002', 'name' => 'Raket Padel Bullpadel Vertex 03 (Sewa)', 'description' => 'Raket padel merek Bullpadel dengan tekstur kasar Topspin untuk kontrol bola dan spin tajam.', 'price' => 50000, 'stock' => 15, 'is_consumable' => false],
            ['item_code' => 'INV-003', 'name' => 'Raket Padel Head Speed Pro (Sewa)', 'description' => 'Raket seimbang (all-around) dengan sensasi sentuhan empuk dan kontrol tinggi di net.', 'price' => 45000, 'stock' => 20, 'is_consumable' => false],
            ['item_code' => 'INV-004', 'name' => 'Raket Padel Wilson Blade V2 (Sewa)', 'description' => 'Raket fleksibel dengan sweet spot lebar, sangat bersahabat untuk pemain intermediate.', 'price' => 45000, 'stock' => 20, 'is_consumable' => false],
            ['item_code' => 'INV-005', 'name' => 'Raket Padel Kuikma PR 990 (Sewa Pemula)', 'description' => 'Raket sewa ringan dengan tingkat toleransi kesalahan tinggi, ideal untuk pemula.', 'price' => 35000, 'stock' => 25, 'is_consumable' => false],
            ['item_code' => 'INV-006', 'name' => 'Bola Padel Head Padel Pro (Slop isi 3)', 'description' => 'Bola resmi Federasi Padel Internasional dengan durabilitas tinggi dan pantulan presisi.', 'price' => 110000, 'stock' => 60, 'is_consumable' => true],
            ['item_code' => 'INV-007', 'name' => 'Bola Padel Bullpadel Premium Pro (Slop isi 3)', 'description' => 'Bola padel berkecepatan tinggi dengan karet bertekanan turnamen kompetitif.', 'price' => 120000, 'stock' => 50, 'is_consumable' => true],
            ['item_code' => 'INV-008', 'name' => 'Overgrip Wilson Pro Comfort (Pack isi 3)', 'description' => 'Grip tambahan lembut anti-licin dengan daya serap keringat maksimal.', 'price' => 45000, 'stock' => 80, 'is_consumable' => true],
            ['item_code' => 'INV-009', 'name' => 'Handgrip ShockOut Anti-Vibration', 'description' => 'Grip khusus penyerap getaran benturan untuk pencegahan cedera tennis elbow.', 'price' => 65000, 'stock' => 40, 'is_consumable' => true],
            ['item_code' => 'INV-010', 'name' => 'Minuman Isotonik Pocari Sweat 500ml', 'description' => 'Minuman pengganti ion tubuh dingin untuk rehidrasi cepat selama pertandingan.', 'price' => 12000, 'stock' => 150, 'is_consumable' => true],
            ['item_code' => 'INV-011', 'name' => 'Hydro Coco Pure Coconut Water 330ml', 'description' => 'Air kelapa murni tanpa pengawet kaya elektrolit alami untuk stamina dan kesegaran.', 'price' => 15000, 'stock' => 100, 'is_consumable' => true],
            ['item_code' => 'INV-012', 'name' => 'Air Mineral Pristine 8+ 600ml', 'description' => 'Air mineral alkali dingin berkualitas tinggi menjaga keseimbangan hidrasi tubuh.', 'price' => 8000, 'stock' => 200, 'is_consumable' => true],
            ['item_code' => 'INV-013', 'name' => 'Handuk Olahraga Microfiber Padel Arena', 'description' => 'Handuk cepat kering berlogo eksklusif Padel Arena, lembut dan nyaman dipakai.', 'price' => 35000, 'stock' => 75, 'is_consumable' => true],
            ['item_code' => 'INV-014', 'name' => 'Wristband Sweatband Padel Arena (Sepasang)', 'description' => 'Gelang tangan elastis penyerap keringat menjaga cengkeraman telapak tangan tetap kering.', 'price' => 25000, 'stock' => 60, 'is_consumable' => true],
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
