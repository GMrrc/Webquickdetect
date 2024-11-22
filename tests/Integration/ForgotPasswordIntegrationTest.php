<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;
use App\Models\User;

class ForgotPasswordIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);
    }

    public function testShowForgotPasswordForm()
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
    }

    public function testSendPasswordResetLink()
    {
        $response = $this->post('/forgot-password', ['email' => $this->user->email]);
        $response->assertStatus(302);
    }

    public function testSendPasswordResetLinkWithInvalidEmail()
    {
        $response = $this->post('/forgot-password', ['email' => 'invalid@example.com']);
        $response->assertStatus(302);
    }

    public function testShowResetPasswordForm()
    {
        $token = Password::createToken($this->user);
        $response = $this->get("/reset_password/{$token}");
        $response->assertStatus(200);
    }

    public function testResetPassword()
    {
        $token = Password::createToken($this->user);
        $password = 'newpassword';

        $response = $this->post('/reset_password', [
            'token' => $token,
            'email' => $this->user->email,
            'new_password' => $password,
            'new_password_confirmation' => $password,
        ]);

        $response->assertStatus(302);
    }

    public function testResetPasswordWithInvalidToken()
    {
        $password = 'newpassword';

        $response = $this->post('/reset_password', [
            'token' => 'invalidtoken',
            'email' => $this->user->email,
            'new_password' => $password,
            'new_password_confirmation' => $password,
        ]);

        $response->assertStatus(302);
    }

    public function testResetPasswordWithInvalidEmail()
    {
        $token = Password::createToken($this->user);
        $password = 'newpassword';

        $response = $this->post('/reset_password', [
            'token' => $token,
            'email' => 'invalid@example.com',
            'new_password' => $password,
            'new_password_confirmation' => $password,
        ]);

        $response->assertStatus(302);
    }
}
