<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Court;
use App\Models\Coach;
use App\Models\Inventory;
use App\Models\Discount;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $courts = Court::where('status', 'available')->get();
        $coaches = Coach::where('is_available', true)->get();
        $inventories = Inventory::all();

        if ($users->isEmpty() || $courts->isEmpty()) {
            return;
        }

        $now = Carbon::now();

        // Preset realistic reservation templates
        $templates = [
            // Past completed reservations (last 1-14 days)
            [
                'code' => 'PADEL-20260901-001',
                'user_offset' => 0,
                'court_offset' => 0,
                'coach_offset' => 0,
                'date' => $now->copy()->subDays(12)->format('Y-m-d'),
                'start_time' => '08:00',
                'end_time' => '10:00',
                'status' => 'completed',
                'discount' => 'WELCOME50',
                'inventories' => [
                    ['code' => 'INV-001', 'qty' => 2],
                    ['code' => 'INV-006', 'qty' => 1],
                    ['code' => 'INV-010', 'qty' => 4],
                ],
                'proof' => 'sample_transfer_proof_01.jpg',
            ],
            [
                'code' => 'PADEL-20260903-002',
                'user_offset' => 1,
                'court_offset' => 1,
                'coach_offset' => null,
                'date' => $now->copy()->subDays(10)->format('Y-m-d'),
                'start_time' => '16:00',
                'end_time' => '18:00',
                'status' => 'completed',
                'discount' => null,
                'inventories' => [
                    ['code' => 'INV-002', 'qty' => 2],
                    ['code' => 'INV-011', 'qty' => 2],
                ],
                'proof' => 'sample_transfer_proof_02.jpg',
            ],
            [
                'code' => 'PADEL-20260905-003',
                'user_offset' => 2,
                'court_offset' => 2,
                'coach_offset' => 1,
                'date' => $now->copy()->subDays(8)->format('Y-m-d'),
                'start_time' => '09:00',
                'end_time' => '11:00',
                'status' => 'completed',
                'discount' => 'POTONGAN50RB',
                'inventories' => [
                    ['code' => 'INV-003', 'qty' => 2],
                    ['code' => 'INV-012', 'qty' => 4],
                ],
                'proof' => 'sample_transfer_proof_03.jpg',
            ],
            [
                'code' => 'PADEL-20260908-004',
                'user_offset' => 3,
                'court_offset' => 3,
                'coach_offset' => null,
                'date' => $now->copy()->subDays(6)->format('Y-m-d'),
                'start_time' => '18:00',
                'end_time' => '20:00',
                'status' => 'completed',
                'discount' => 'WEEKEND20',
                'inventories' => [
                    ['code' => 'INV-004', 'qty' => 4],
                    ['code' => 'INV-007', 'qty' => 2],
                ],
                'proof' => 'sample_transfer_proof_04.jpg',
            ],
            [
                'code' => 'PADEL-20260910-005',
                'user_offset' => 4,
                'court_offset' => 4,
                'coach_offset' => 2,
                'date' => $now->copy()->subDays(4)->format('Y-m-d'),
                'start_time' => '14:00',
                'end_time' => '16:00',
                'status' => 'completed',
                'discount' => null,
                'inventories' => [
                    ['code' => 'INV-001', 'qty' => 2],
                    ['code' => 'INV-008', 'qty' => 2],
                ],
                'proof' => 'sample_transfer_proof_05.jpg',
            ],
            [
                'code' => 'PADEL-20260912-006',
                'user_offset' => 5,
                'court_offset' => 5,
                'coach_offset' => null,
                'date' => $now->copy()->subDays(2)->format('Y-m-d'),
                'start_time' => '19:00',
                'end_time' => '21:00',
                'status' => 'completed',
                'discount' => 'STUDENT10',
                'inventories' => [
                    ['code' => 'INV-002', 'qty' => 2],
                    ['code' => 'INV-010', 'qty' => 3],
                ],
                'proof' => 'sample_transfer_proof_06.jpg',
            ],
            [
                'code' => 'PADEL-20260914-007',
                'user_offset' => 6,
                'court_offset' => 0,
                'coach_offset' => 3,
                'date' => $now->copy()->subDays(1)->format('Y-m-d'),
                'start_time' => '10:00',
                'end_time' => '12:00',
                'status' => 'completed',
                'discount' => 'POTONGAN50RB',
                'inventories' => [
                    ['code' => 'INV-005', 'qty' => 2],
                    ['code' => 'INV-012', 'qty' => 2],
                ],
                'proof' => 'sample_transfer_proof_07.jpg',
            ],

            // Today's Reservations (Confirmed and Pending)
            [
                'code' => 'PADEL-20260915-008',
                'user_offset' => 0,
                'court_offset' => 1,
                'coach_offset' => null,
                'date' => $now->format('Y-m-d'),
                'start_time' => '15:00',
                'end_time' => '17:00',
                'status' => 'confirmed',
                'discount' => 'WEEKEND20',
                'inventories' => [
                    ['code' => 'INV-001', 'qty' => 2],
                    ['code' => 'INV-006', 'qty' => 1],
                ],
                'proof' => 'sample_transfer_proof_08.jpg',
            ],
            [
                'code' => 'PADEL-20260915-009',
                'user_offset' => 1,
                'court_offset' => 2,
                'coach_offset' => 0,
                'date' => $now->format('Y-m-d'),
                'start_time' => '18:00',
                'end_time' => '20:00',
                'status' => 'confirmed',
                'discount' => null,
                'inventories' => [
                    ['code' => 'INV-003', 'qty' => 2],
                    ['code' => 'INV-010', 'qty' => 4],
                ],
                'proof' => 'sample_transfer_proof_09.jpg',
            ],

            // Upcoming Confirmed (Next 1-5 days)
            [
                'code' => 'PADEL-20260916-010',
                'user_offset' => 2,
                'court_offset' => 3,
                'coach_offset' => null,
                'date' => $now->copy()->addDays(1)->format('Y-m-d'),
                'start_time' => '07:00',
                'end_time' => '09:00',
                'status' => 'confirmed',
                'discount' => 'EARLYBIRD15',
                'inventories' => [
                    ['code' => 'INV-004', 'qty' => 2],
                ],
                'proof' => 'sample_transfer_proof_10.jpg',
            ],
            [
                'code' => 'PADEL-20260917-011',
                'user_offset' => 3,
                'court_offset' => 4,
                'coach_offset' => 4,
                'date' => $now->copy()->addDays(2)->format('Y-m-d'),
                'start_time' => '16:00',
                'end_time' => '18:00',
                'status' => 'confirmed',
                'discount' => 'PADELMANIA25',
                'inventories' => [
                    ['code' => 'INV-001', 'qty' => 2],
                    ['code' => 'INV-007', 'qty' => 1],
                ],
                'proof' => 'sample_transfer_proof_11.jpg',
            ],
            [
                'code' => 'PADEL-20260918-012',
                'user_offset' => 4,
                'court_offset' => 5,
                'coach_offset' => null,
                'date' => $now->copy()->addDays(3)->format('Y-m-d'),
                'start_time' => '19:00',
                'end_time' => '21:00',
                'status' => 'confirmed',
                'discount' => null,
                'inventories' => [
                    ['code' => 'INV-002', 'qty' => 2],
                    ['code' => 'INV-010', 'qty' => 2],
                ],
                'proof' => 'sample_transfer_proof_12.jpg',
            ],

            // Pending Verifications (Awaiting Admin Review)
            [
                'code' => 'PADEL-20260919-013',
                'user_offset' => 5,
                'court_offset' => 6,
                'coach_offset' => 1,
                'date' => $now->copy()->addDays(4)->format('Y-m-d'),
                'start_time' => '09:00',
                'end_time' => '11:00',
                'status' => 'pending',
                'discount' => 'POTONGAN50RB',
                'inventories' => [
                    ['code' => 'INV-003', 'qty' => 2],
                    ['code' => 'INV-008', 'qty' => 1],
                ],
                'proof' => 'sample_transfer_proof_13.jpg',
            ],
            [
                'code' => 'PADEL-20260920-014',
                'user_offset' => 6,
                'court_offset' => 7,
                'coach_offset' => null,
                'date' => $now->copy()->addDays(5)->format('Y-m-d'),
                'start_time' => '17:00',
                'end_time' => '19:00',
                'status' => 'pending',
                'discount' => 'FLASHDEAL75K',
                'inventories' => [
                    ['code' => 'INV-005', 'qty' => 4],
                    ['code' => 'INV-012', 'qty' => 4],
                ],
                'proof' => 'sample_transfer_proof_14.jpg',
            ],
            [
                'code' => 'PADEL-20260921-015',
                'user_offset' => 0,
                'court_offset' => 8,
                'coach_offset' => 2,
                'date' => $now->copy()->addDays(6)->format('Y-m-d'),
                'start_time' => '15:00',
                'end_time' => '17:00',
                'status' => 'pending',
                'discount' => null,
                'inventories' => [
                    ['code' => 'INV-001', 'qty' => 2],
                ],
                'proof' => null, // Waiting payment upload
            ],
            // Cancelled reservation sample
            [
                'code' => 'PADEL-20260922-016',
                'user_offset' => 1,
                'court_offset' => 9,
                'coach_offset' => null,
                'date' => $now->copy()->subDays(5)->format('Y-m-d'),
                'start_time' => '13:00',
                'end_time' => '15:00',
                'status' => 'cancelled',
                'discount' => null,
                'inventories' => [],
                'proof' => null,
            ],
        ];

        foreach ($templates as $t) {
            $user = $users->get($t['user_offset'] % $users->count());
            $court = $courts->get($t['court_offset'] % $courts->count());
            $coach = ($t['coach_offset'] !== null && $coaches->isNotEmpty())
                ? $coaches->get($t['coach_offset'] % $coaches->count())
                : null;

            // Calculate duration in hours
            $start = Carbon::createFromTimeString($t['start_time']);
            $end = Carbon::createFromTimeString($t['end_time']);
            $durationHours = max(1, $start->diffInHours($end));

            $courtPrice = $court->price_per_hour * $durationHours;
            $coachPrice = $coach ? ($coach->price_per_hour * $durationHours) : 0;
            $totalPrice = $courtPrice + $coachPrice;

            // Calculate inventory add-on costs
            $inventoryAttaches = [];
            foreach ($t['inventories'] as $invItem) {
                $inv = $inventories->firstWhere('item_code', $invItem['code']);
                if ($inv) {
                    $itemTotal = $inv->price * $invItem['qty'];
                    $totalPrice += $itemTotal;
                    $inventoryAttaches[$inv->id] = [
                        'quantity' => $invItem['qty'],
                        'price' => $inv->price,
                    ];
                }
            }

            // Calculate discount
            $discountAmount = 0;
            $discountCode = $t['discount'];
            if ($discountCode) {
                $discountModel = Discount::where('code', $discountCode)->first();
                if ($discountModel) {
                    if ($discountModel->type === 'percentage') {
                        $discountAmount = ($totalPrice * $discountModel->percentage) / 100;
                    } else {
                        $discountAmount = min($totalPrice, $discountModel->nominal_amount ?? 0);
                    }
                }
            }

            $finalPrice = max(0, $totalPrice - $discountAmount);

            $reservation = Reservation::updateOrCreate(
                ['reservation_code' => $t['code']],
                [
                    'user_id' => $user->id,
                    'court_id' => $court->id,
                    'coach_id' => $coach ? $coach->id : null,
                    'reservation_date' => $t['date'],
                    'start_time' => $t['start_time'],
                    'end_time' => $t['end_time'],
                    'total_price' => $totalPrice,
                    'discount_code' => $discountCode,
                    'discount_amount' => $discountAmount,
                    'final_price' => $finalPrice,
                    'status' => $t['status'],
                    'payment_proof' => $t['proof'],
                ]
            );

            // Sync pivot inventory items
            if (!empty($inventoryAttaches)) {
                $reservation->inventories()->sync($inventoryAttaches);
            }
        }
    }
}
