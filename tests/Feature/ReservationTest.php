<?php

namespace Tests\Feature;

use App\Models\Court;
use App\Models\Coach;
use App\Models\User;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;
    protected Court $court;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Booking Player',
            'email' => 'booking_' . uniqid() . '@example.com',
            'password' => bcrypt('secret123'),
            'role' => 'user',
        ]);

        $this->court = Court::create([
            'name' => 'Court Test Alpha',
            'description' => 'Test court description',
            'type' => 'Indoor',
            'price_per_hour' => 150000,
            'status' => 'available',
        ]);
    }

    public function test_guest_is_redirected_when_accessing_reservation_create(): void
    {
        $response = $this->get('/user/reservations/create');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_reservation_form(): void
    {
        $response = $this->actingAs($this->user)->get('/user/reservations/create');
        $response->assertStatus(200);
        $response->assertSee('Court Test Alpha');
    }

    public function test_booked_slots_api_returns_correct_json_response(): void
    {
        $targetDate = Carbon::tomorrow()->toDateString();

        Reservation::create([
            'reservation_code' => 'RES-' . strtoupper(uniqid()),
            'user_id' => $this->user->id,
            'court_id' => $this->court->id,
            'reservation_date' => $targetDate,
            'start_time' => '10:00',
            'end_time' => '12:00',
            'total_price' => 300000,
            'final_price' => 300000,
            'status' => 'confirmed',
        ]);

        $response = $this->actingAs($this->user)->getJson(
            route('user.api.booked.slots', [
                'court_id' => $this->court->id,
                'date' => $targetDate,
            ])
        );

        $response->assertStatus(200);
        $response->assertJsonFragment([
            'start' => '10:00',
            'end' => '12:00',
        ]);
    }

    public function test_user_can_create_a_valid_court_reservation(): void
    {
        $targetDate = Carbon::tomorrow()->toDateString();

        $response = $this->actingAs($this->user)->post(route('user.reservations.store'), [
            'court_id' => $this->court->id,
            'reservation_date' => $targetDate,
            'start_time' => '14:00',
            'end_time' => '16:00',
        ]);

        $this->assertDatabaseHas('reservations', [
            'user_id' => $this->user->id,
            'court_id' => $this->court->id,
            'reservation_date' => $targetDate,
            'status' => 'pending',
        ]);

        $response->assertRedirect();
    }

    public function test_reservation_validation_fails_for_past_dates(): void
    {
        $pastDate = Carbon::yesterday()->toDateString();

        $response = $this->actingAs($this->user)->post(route('user.reservations.store'), [
            'court_id' => $this->court->id,
            'reservation_date' => $pastDate,
            'start_time' => '14:00',
            'end_time' => '16:00',
        ]);

        $response->assertSessionHasErrors(['reservation_date']);
    }
}
