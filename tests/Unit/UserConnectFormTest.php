<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserConnectFormTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get(route('login'));

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('state', 'login');
    }

    public function testConnectWithValidCredentials()
    {
        $password = 'Password123!';

        $user = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'johndoe@example.com',
            'password' => bcrypt($password)
        ]);

        $response = $this->post(route('login'), [
            'email' => 'johndoe@example.com',
            'password' => $password,
        ]);

        $response->assertRedirect(route('home'));
        $this->assertAuthenticatedAs($user);
    }

    public function testConnectWithInvalidCredentials()
    {

        $user = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'johndoe@example.com',
            'password' => bcrypt("Password123!")
        ]);

        $response = $this->post(route('login'), [
            'email' => 'johndoe@example.com',
            'password' => 'WrongPassword',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function testConnectWithNonExistentUser()
    {
        $response = $this->post(route('login'), [
            'email' => 'nonexistent@example.com',
            'password' => 'Password123!',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function testConnectValidationErrors()
    {
        $response = $this->post(route('login'), [
            'email' => 'invalid-email',
            'password' => '',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['email', 'password']);
        $this->assertGuest();
    }
}
