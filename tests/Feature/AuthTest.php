<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function login_page_shows_for_guest()
    {
        $response = $this->get('/signin');
        $response->assertStatus(200);
        $response->assertViewIs('pages.auth.signin');
    }

    /** @test */
    public function authenticated_user_redirected_from_login()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/signin');
        $response->assertRedirect(route('dashboard'));
    }

    /** @test */
    public function user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/signin', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function user_cannot_login_with_invalid_password()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/signin', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /** @test */
    public function user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/logout');

        $response->assertRedirect('/');
        $this->assertGuest();
    }

    /** @test */
    public function protected_routes_redirect_to_login()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect('/signin');

        $response = $this->get(route('dirigentes.index'));
        $response->assertRedirect('/signin');
    }

    /** @test */
    public function authenticated_user_can_access_protected_routes()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('dashboard'));
        $response->assertStatus(200);
    }
}
