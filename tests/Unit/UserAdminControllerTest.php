<?php

namespace Tests\Feature\Admin;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Controllers\Admin\UserAdminController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Library;
use App\Models\Picture;
use App\Models\Video;

class UserAdminControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;

    protected function setUp(): void
    {
        parent::setUp();

        $user = new User();
        $user->name = 'John';
        $user->surname = 'Doe';
        $user->dateOfBirth = '1990-01-01';
        $user->role = 'admin';
        $user->email = 'johnDoe@gmail.com';
        $user->password = Hash::make('Password123!');
        $user->save();

        $this->user = $user;

        Auth::login($user);
    }

    public function testIndex()
    {
        $response = $this->get(route('admin.UserAdmin.index'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.Users.index');
        $response->assertViewHas('users');
    }

    public function testCreate()
    {
        $response = $this->get(route('admin.UserAdmin.create'));
        $response->assertStatus(200);
        $response->assertViewIs('admin.Users.form');
    }

    public function testStore()
    {
        $data = [
            'nom' => 'John',
            'prenom' => 'Doe',
            'dateNaissance' => '1990-01-01',
            'role' => 'admin',
            'email' => 'johndoe@example.com',
            'motDePasse' => 'Password123!',
        ];

        $response = $this->post(route('admin.UserAdmin.store'), $data);

        $response->assertRedirect(route('admin.UserAdmin.index'));
        $response->assertSessionHas('success', 'Le User a bien été créé');
        $this->assertDatabaseHas('users', ['email' => 'johndoe@example.com']);
    }

    public function testEdit()
    {
        $user = new User();
        $user->name = 'John';
        $user->surname = 'Doe';
        $user->dateOfBirth = '1990-01-01';
        $user->role = 'admin';
        $user->email = 'johnDoe2@gmail.com';
        $user->password = Hash::make('Password123!');
        $user->save();

        $response = $this->get(route('admin.UserAdmin.edit', $user->idUser));
        $response->assertStatus(200);
        $response->assertViewIs('admin.Users.form');
        $response->assertViewHas('User', $user);
    }

    public function testUpdate()
    {
        $data = [
            'nom' => 'Jane',
            'prenom' => 'Doe',
            'dateNaissance' => '1990-01-01',
            'role' => 'admin',
            'email' => 'janedoe@example.com',
        ];

        $response = $this->put(route('admin.UserAdmin.update', $this->user->idUser), $data);

        $response->assertRedirect(route('admin.UserAdmin.index'));
        $response->assertSessionHas('success', 'Le user a bien été modifié');
        $this->assertDatabaseHas('users', ['email' => 'janedoe@example.com']);
    }
    /*
    public function testDestroy()
    {
        $user = new User();
        $user->name = 'John';
        $user->surname = 'Doe';
        $user->dateOfBirth = '1990-01-01';
        $user->role = 'user';
        $user->email = 'supjohn@gmail.com';
        $user->password = Hash::make('Password123!');

        $user->save();

        $library = Library::create([
            'name' => 'Main Library',
            'idUser' => $user->idUser
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

        $video = Video::create([
            'title' => 'Sunset',
            'format' => 'mp4',
            'size' => 1.5,
            'path' => 'path/to/video',
            'data' => 'json',
            'idLibrary' => $library->idLibrary
        ]);

        $video->save();

        $response = UserAdminController::destroy( $user->idUser);

        $response->assertRedirect(route('admin.UserAdmin.index'));
        $response->assertSessionHas('success', 'Le User a bien été supprimé');
    }

    public function testDestroyLibrary()
    {
        $user = User::factory()->create();
        $library = Library::factory()->create(['idUser' => $user->idUser]);
        $picture = Picture::factory()->create(['idLibrary' => $library->idLibrary]);
        $video = Video::factory()->create(['idLibrary' => $library->idLibrary]);

        $response = $this->delete(route('admin.UserAdmin.destroyLibrairy', $user->idUser));

        $response->assertRedirect(route('admin.UserAdmin.index'));
        $response->assertSessionHas('success', 'Les librairies sont supprimées');
        $this->assertDatabaseMissing('libraries', ['idLibrary' => $library->idLibrary]);
        $this->assertDatabaseMissing('pictures', ['idLibrary' => $library->idLibrary]);
        $this->assertDatabaseMissing('videos', ['idLibrary' => $library->idLibrary]);
    }*/
}
