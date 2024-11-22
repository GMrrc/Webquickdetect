<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    public function testShowForgotPasswordForm()
    {
        $response = $this->get('/forgot-password');
        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('state', 'forgot_password');
    }

    public function testSendPasswordResetLink()
    {
        $user = new User();
        $user->name = 'test';
        $user->surname = 'test';
        $user->dateOfBirth = '1990-01-01';
        $user->role = 'user';
        $user->email = 'test@example.com';
        $user->password = bcrypt('password');
        $user->save();


        $response = $this->post('/forgot-password', [
            'email' => 'test@example.com',

        ]);

        $response->assertStatus(302);
        $response->assertSessionHas('success', 'Un mail vous a été envoyé');
    }

    public function testSendPasswordResetLinkWithInvalidEmail()
    {
        $response = $this->post('/forgot-password', [
            'email' => 'invalid@example.com',
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email' => "L'email fourni n'existe pas dans nos enregistrements."]);
    }

    public function testShowResetPasswordForm()
    {
        $response = $this->get('/reset_password/testtoken');
        $response->assertStatus(200);
        $response->assertViewIs('reset_password');
        $response->assertViewHas('token', 'testtoken');
    }

    public function testResetPassword()
    {

        $user = new User();
        $user->name = 'test';
        $user->surname = 'test';
        $user->dateOfBirth = '1990-01-01';
        $user->role = 'user';
        $user->email = 'test@example.com';
        $user->password = bcrypt('oldpassword');
        $user->save();

        PasswordResetToken::create([
            'email' => 'test@example.com',
            'token' => hash_hmac('sha256', 'testtoken', config('app.token_key')),
            'created_at' => now(),
        ]);

        $response = $this->post('/reset_password', [
            'token' => 'testtoken',
            'email' => 'test@example.com',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/login');
        $response->assertSessionHas('success', 'Votre mot de passe a été réinitialisé avec succès.');

        $user->refresh();
        $this->assertTrue(Hash::check('newpassword', $user->password));
    }

    public function testResetPasswordWithInvalidToken()
    {
        $user = new User();
        $user->name = 'test';
        $user->surname = 'test';
        $user->dateOfBirth = '1990-01-01';
        $user->role = 'user';
        $user->email = 'test@example.com';
        $user->password = bcrypt('oldpassword');
        $user->save();

        $response = $this->post('/reset_password', [
            'token' => 'invalidtoken',
            'email' => 'test@example.com',
            'new_password' => 'newpassword',
            'new_password_confirmation' => 'newpassword'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors(['email' => "Le token fourni n'est pas valide ou a expiré."]);
    }

    public function testResetPasswordWithValidationErrors()
    {
        $response = $this->post('/reset_password', [
            'token' => '',
            'email' => 'invalid',
            'new_password' => 'short',
            'new_password_confirmation' => 'nomatch'
        ]);

        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'token', 
            'email', 
            'new_password', 
            'new_password_confirmation'
        ]);
    }
}
