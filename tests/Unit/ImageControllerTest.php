<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Picture;
use App\Models\Library;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ImageControllerTest extends TestCase
{
    use RefreshDatabase;
    private $user;
    
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

        $user->save();

        $library = Library::create([
            'name' => 'Main Library',
            'idUser' => $user->idUser
        ]);

        $library->save();

        $this->user = $user;
        
        Auth::login($this->user);
    }

    public function testIndexWhenAuthenticated()
    {
        $response = $this->get('/image-processing');

        $response->assertStatus(200);
        $response->assertViewIs('images_processing');
    }

    public function testIndexWhenNotAuthenticated()
    {
        Auth::logout();

        $response = $this->get('/image-processing');

        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUploadWithValidFile()
    {
        Storage::fake('local');
        $file = \Illuminate\Http\UploadedFile::fake()->image('test_image.jpg');

        $response = $this->post('/image-processing', [
            'file' => [$file], 
            'accuracy' => 0.9,
            'modeleVersion' => 'v1',
            'modeleTask' => 'detection',
            'score' => 0.8,
            'max_det' => 10,
            'libraryName' => 'Test Library'
        ]);

        $response->assertStatus(200);
    }

    public function testUploadWithNoFile()
    {

        Auth::login($this->user);

        Storage::fake('local');
        $file = UploadedFile::fake()->image('test_image.jpg');

        $response = $this->post('/image-processing', [
            'file' => [$file],
            'accuracy' => 0.9,
            'modeleVersion' => 'v1',
            'modeleTask' => 'detection',
            'score' => 0.8,
            'max_det' => 10,
            'libraryName' => 'Test Library'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('error');
    }

    public function testShowImageWhenAuthenticated()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->image('test.jpg');

        $user = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'jhonny@example.com',
            'password' => bcrypt('password')
        ]);

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

        Storage::put($file->hashName(), '');

        $response = $this->get('/images/' . $picture->idPicture);

        $response->assertStatus(200);
    }

    public function testShowImageWhenNotAuthenticated()
    {
        Auth::logout();

        $response = $this->get('/images/1');

        $response->assertStatus(200);
        $response->assertViewIs('error');
        $response->assertViewHas('data.error', 'You must be connected to access this page');
    }

    public function testDeleteImageWhenAuthenticated()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->image('test.jpg');

        $library = Library::create([
            'name' => 'Main Library',
            'idUser' => $this->user->idUser
        ]);

        $picture = Picture::create([
            'title' => 'test',
            'format' => 'jpg',
            'size' => 1.5,
            'path' => 'images/' . $this->user->idUser . '/test.jpg',
            'idLibrary' => $library->idLibrary
        ]);

        Storage::put($picture->path, $file->getContent());

        $response = $this->delete('/images/' . $picture->idPicture);

        $response->assertStatus(302);
        $response->assertRedirect(route('library.show', ['id' => $library->idLibrary]));
        $this->assertDatabaseMissing('pictures', ['idPicture' => $picture->idPicture]);
    }

    public function testDeleteImageWhenNotAuthenticated()
    {
        Auth::logout();

        $response = $this->delete('/images/1');

        $response->assertStatus(200);
        $response->assertViewIs('error');
        $response->assertViewHas('data.error', 'You must be connected to access this page');
    }
}
