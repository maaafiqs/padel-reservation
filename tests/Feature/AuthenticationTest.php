<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
    }

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_users_can_register_and_access_user_dashboard(): void
    {
        $uniqueEmail = 'testuser_' . uniqid() . '@example.com';
        $response = $this->post('/register', [
            'name' => 'Test Player',
            'email' => $uniqueEmail,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $uniqueEmail = 'player_' . uniqid() . '@example.com';
        $user = User::create([
            'name' => 'Regular Player',
            'email' => $uniqueEmail,
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        $response = $this->post('/login', [
            'email' => $uniqueEmail,
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('user.dashboard'));
    }

    public function test_admin_can_login_and_is_redirected_to_admin_dashboard(): void
    {
        $uniqueEmail = 'admin_' . uniqid() . '@example.com';
        $admin = User::create([
            'name' => 'Site Administrator',
            'email' => $uniqueEmail,
            'password' => Hash::make('secret123'),
            'role' => 'admin',
        ]);

        $response = $this->post('/login', [
            'email' => $uniqueEmail,
            'password' => 'secret123',
        ]);

        $this->assertAuthenticatedAs($admin);
        $response->assertRedirect(route('admin.dashboard'));
    }

    public function test_users_cannot_authenticate_with_invalid_password(): void
    {
        $uniqueEmail = 'invalid_' . uniqid() . '@example.com';
        $user = User::create([
            'name' => 'Regular Player',
            'email' => $uniqueEmail,
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        $this->post('/login', [
            'email' => $uniqueEmail,
            'password' => 'wrong-password',
        ]);

        $this->assertGuest();
    }

    public function test_regular_user_cannot_access_admin_dashboard(): void
    {
        $uniqueEmail = 'user_' . uniqid() . '@example.com';
        $user = User::create([
            'name' => 'Regular Player',
            'email' => $uniqueEmail,
            'password' => Hash::make('secret123'),
            'role' => 'user',
        ]);

        $response = $this->actingAs($user)->get('/admin/dashboard');
        // Route middleware returns 403 or redirect for forbidden role
        $this->assertTrue(in_array($response->getStatusCode(), [403, 302]));
    }
}
