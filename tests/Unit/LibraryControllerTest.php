<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Library;
use App\Models\Picture;
use App\Models\Video;

class LibraryControllerTest extends TestCase
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
        $user->role = 'user';
        $user->email = 'johnDoe@test.com';
        $user->password = bcrypt('password');

        $user->save();
        $this->user = $user;

        $this->assertNotNull($this->user, 'Failed to create user');
        Auth::login($this->user);
        $this->assertTrue(Auth::check(), 'Failed to log in user');
    }

    public function testIndexRedirectsIfNotAuthenticated()
    {
        Auth::logout();
        $response = $this->get(route('library.index'));
        $response->assertRedirect(route('login'));
    }

    public function testIndexShowsLibrariesForAuthenticatedUser()
    {

        $library = Library::create([
            'name' => 'Main Library',
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

        $response = $this->get(route('library.index'));

        $response->assertStatus(200);
        $response->assertViewIs('libraries');
        $response->assertViewHas('data.libraries');
    }

    public function testShowRedirectsIfNotAuthenticated()
    {
        Auth::logout();
        $response = $this->get(route('library.show', ['id' => 1]));
        $response->assertViewIs('error');
        $response->assertViewHas('error', 'You must be connected to access this page');
    }

    public function testShowLibraryForAuthenticatedUser()
    {

        $library = Library::create([
            'name' => 'Main Library',
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

        $video = Video::create([
            'title' => 'Sunset',
            'format' => 'mp4',
            'size' => 1.5,
            'path' => 'path/to/video',
            'data' => 'json',
            'idLibrary' => $library->idLibrary
        ]);

        $video->save();

        $response = $this->get(route('library.show', ['id' => $library->idLibrary]));

        $response->assertStatus(200);
        $response->assertViewIs('library');
        $response->assertViewHas('library', $library);
        $response->assertViewHas('pictures');
        $response->assertViewHas('videos');
    }

    public function testDeleteLibrary()
    {
        $library = Library::create([
            'name' => 'Main Library',
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

        Storage::fake('local');
        Storage::put($picture->path, 'fake content');

        $response = $this->delete(route('library.delete', ['id' => $library->idLibrary]));

        $response->assertRedirect(route('library.index'));
        $response->assertSessionHas('success', 'Librairie supprimée avec succès.');
        $this->assertDatabaseMissing('library', ['idLibrary' => $library->idLibrary]);
        $this->assertDatabaseMissing('pictures', ['idLibrary' => $library->idLibrary]);
    }

}
