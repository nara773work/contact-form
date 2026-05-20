<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_login(): void
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
    }

    public function test_login_crrect(): void
    {
        // Arrange
        $user = User::factory()->create([
            'password' => bcrypt('password123'),
        ]);

        // Act
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // Assert
        $this->assertAuthenticatedAs($user);
    }

    public function test_null_email(): void
    {
        // Act
        $response = $this->post(route('login'), [
            'email' => '',
            'password' => 'password123',
        ]);

        // Assert
        $response->assertSessionHasErrors('email');
    }

    /** @test */
    public function test_null_pass(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->post(route('login'), [
            'email' => $user->email,
            'password' => '',
        ]);

        // Assert
        $response->assertSessionHasErrors('password');
    }

    public function test_logout(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)->post(route('logout'));

        // Assert
        $this->assertGuest();
    }
}
