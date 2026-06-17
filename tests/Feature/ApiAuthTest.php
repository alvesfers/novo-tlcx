<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function api_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => ['user', 'token'],
            'message',
        ]);
        $this->assertTrue($response->json('success'));
        $this->assertNotEmpty($response->json('data.token'));
    }

    /** @test */
    public function api_login_with_invalid_credentials()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
        $response->assertJson(['success' => false]);
    }

    /** @test */
    public function api_me_endpoint_returns_current_user()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->getJson('/api/auth/me', [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'data' => ['id' => $user->id, 'email' => $user->email],
        ]);
    }

    /** @test */
    public function api_logout_revokes_token()
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $response = $this->postJson('/api/auth/logout', [], [
            'Authorization' => "Bearer $token",
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Token should be revoked
        $response = $this->getJson('/api/auth/me', [
            'Authorization' => "Bearer $token",
        ]);
        $response->assertStatus(401);
    }

    /** @test */
    public function api_requires_authentication()
    {
        $response = $this->getJson('/api/dirigentes');
        $response->assertStatus(401);
    }
}
