<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LogoutControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $user = new User();
        $user->name = 'John';
        $user->surname = 'Doe';
        $user->role = 'user';
        $user->dateOfBirth = '1990-01-01';
        $user->email = 'doeJohn@gmail.com';
        $user->password = Hash::make('password');

        $user->save();

        $this->user = $user;
    }

    public function testLogout()
    {
        $this->actingAs($this->user);

        $this->assertAuthenticatedAs($this->user);

        $response = $this->get(route('logout'));

        $response->assertRedirect('home');

        $this->assertGuest();
    }
}
