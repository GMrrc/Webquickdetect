<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Models\User;
use App\Models\Library;
use App\Models\Picture;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

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

        Auth::login($user);
    }

    public function testIndex()
    {
        $response = $this->get(route('register'));
        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('state', 'register');
    }

    public function testCreate()
    {
        Event::fake();

        $data = [
            'terms' => true,
            'email' => 'johndoe@example.com',
            'password1' => 'Password123!',
            'password2' => 'Password123!',
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
        ];

        $response = $this->post(route('register'), $data);

        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('state', 'verify-email');
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);

        Event::assertDispatched(Registered::class);
    }

    public function testParametre()
    {
        $response = $this->get(route('parametre'));
        $response->assertStatus(200);
        $response->assertViewIs('user_parametre');
        $response->assertViewHas('user', $this->user);
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'Jane',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'email' => 'janedoe@example.com',
        ];

        $response = $this->post(route('parametre'), $data);

        $response->assertRedirect(route('parametre'));
        $response->assertSessionHas('success', 'Profil mis à jour avec succès');
        $this->assertDatabaseHas('users', ['email' => 'janedoe@example.com']);
    }

    public function testDestroy()
    {

        $library = Library::create([
            'name' => 'Main Library 2',
            'idUser' => $this->user->idUser
        ]);

        $library->save();

        $picture = Picture::create([
            'title' => 'Sunset',
            'format' => 'jpg',
            'size' => 1.5,
            'path' => 'path/to/image',
            'idLibrary' => $library->idLibrary
        ]);

        $picture->save();

        $response = $this->delete(route('parametre'));

        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Profile supprimé avec succès');
        $this->assertDatabaseMissing('users', ['idUser' => $this->user->idUser]);
        $this->assertDatabaseMissing('library', ['idLibrary' => $library->idLibrary]);
        $this->assertDatabaseMissing('pictures', ['idLibrary' => $library->idLibrary]);
    }

    public function testEditPassword()
    {
        $response = $this->get(route('new-password'));
        $response->assertStatus(200);
        $response->assertViewIs('user_new_password');
        $response->assertViewHas('user', $this->user);
    }

    public function testUpdatePassword()
    {
        $data = [
            'old_password' => 'password',
            'new_password' => 'NewPassword123!',
            'new_password_confirmation' => 'NewPassword123!',
        ];

        $response = $this->post(route('new-password'), $data);

        $response->assertRedirect(route('parametre'));
        $response->assertSessionHas('success', 'Mot de passe mis à jour avec succès');
        $this->assertTrue(Hash::check('NewPassword123!', $this->user->refresh()->password));
    }

}
