<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;

class WebcamControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);

        $this->actingAs($user);
    }

    public function testIndex()
    {
        $response = $this->get(route('webcam_processing'));
        $response->assertStatus(200);
        $response->assertViewIs('webcam_processing');
    }
}
