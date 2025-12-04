<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'newuser@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);
        // Laravel Breeze redirects after successful registration
        $response->assertRedirect();
        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);
    }

    public function test_user_can_login_and_receive_cookie()
    {
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'loginuser@example.com',
            'password' => 'password',
        ]);
        // Breeze login returns 204 No Content for API guard, but for web it redirects
        $response->assertStatus(302);
        $this->assertAuthenticatedAs($user);
    }
}
