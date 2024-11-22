<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Picture;
use App\Models\Library;
use App\Models\Video;
use Illuminate\Support\Facades\Log;

class DownloadControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $library;
    protected $picture;
    protected $video;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '01-01-1990',
            'role' => 'user',
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);

        $this->library = Library::create([
            'name' => 'Main Library',
            'idUser' => $this->user->idUser
        ]);

        $this->picture = Picture::create([
            'title' => 'test_image',
            'format' => 'jpg',
            'size' => 100,
            'path' => "images/{$this->user->idUser}/test_image.jpg",
            'idLibrary' => $this->library->idLibrary,
            'data' => 'json'
        ]);

        $this->video = Video::create([
            'title' => 'test_video',
            'format' => 'mp4',
            'size' => 1000,
            'path' => "videos/{$this->user->idUser}/test_video.mp4",
            'idLibrary' => $this->library->idLibrary,
            'data' => 'json'
        ]);

        Storage::fake('local');
        Storage::put("storage/app/images/{$this->user->idUser}/test_image.jpg", 'fake-image-content');
        Storage::put("storage/app/videos/{$this->user->idUser}/test_video.mp4", 'fake-video-content');
    }

    public function testDownloadImage()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('download.image', ['id' => $this->picture->idPicture]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/jpeg');
        $response->assertHeader('Content-Disposition', 'attachment; filename="test_image.jpg"');
    }

    public function testDownloadJsonImage()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('download.jsonImage', ['id' => $this->picture->idPicture]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $this->picture->title . '.json"');
    }

    public function testDownloadVideo()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('download.video', ['id' => $this->video->idVideo]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'video/mp4');
        $response->assertHeader('Content-Disposition', 'attachment; filename="test_video.mp4"');
    }

    public function testDownloadJsonVideo()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('download.jsonVideo', ['id' => $this->video->idVideo]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $this->video->title . '.json"');
    }

    public function testDownloadLibrary()
    {
        $this->actingAs($this->user);
        $this->library->pictures()->save($this->picture);
        $this->assertTrue(Storage::disk('local')->exists("storage/app/images/{$this->user->idUser}/test_image.jpg"));

        $response = $this->get(route('download.library', ['id' => $this->picture->idPicture]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Disposition', 'attachment; filename="library_' . $this->library->name . '.zip"');
    }

    public function testDownloadImageNotAuthenticated()
    {
        Auth::logout();

        $response = $this->get(route('download.image', ['id' => $this->picture->idPicture]));

        $response->assertStatus(403);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You must be connected to download an image.']);
    }

    public function testDownloadJsonImageNotAuthenticated()
    {
        Auth::logout();

        $response = $this->get(route('download.jsonImage', ['id' => $this->picture->idPicture]));

        $response->assertStatus(403);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You must be connected to download an image.']);
    }

    public function testDownloadVideoNotAuthenticated()
    {
        Auth::logout();

        $response = $this->get(route('download.video', ['id' => $this->video->idVideo]));

        $response->assertStatus(403);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You must be connected to download a video.']);
    }

    public function testDownloadJsonVideoNotAuthenticated()
    {
        Auth::logout();

        $response = $this->get(route('download.jsonVideo', ['id' => $this->video->idVideo]));

        $response->assertStatus(403);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You must be connected to download a video.']);
    }

    public function testDownloadLibraryNotAuthenticated()
    {
        $response = $this->get(route('download.library', ['id' => $this->picture->idPicture]));

        $response->assertStatus(403);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You must be connected to download an image or video.']);
    }

    public function testDownloadImageNotOwned()
    {
        $anotherUser = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'john2@example.com',
            'password' => bcrypt('password')
        ]);


        $this->actingAs($anotherUser);

        $response = $this->get(route('download.image', ['id' => $this->picture->idPicture]));

        $response->assertStatus(404);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You are not allowed to download this image.']);
    }

    public function testDownloadJsonImageNotOwned()
    {
        
        $anotherUser = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'john2@example.com',
            'password' => bcrypt('password')
        ]);


        $this->actingAs($anotherUser);

        $response = $this->get(route('download.jsonImage', ['id' => $this->picture->idPicture]));

        $response->assertStatus(404);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You are not allowed to download this image.']);
    }

    public function testDownloadVideoNotOwned()
    {
        
        $anotherUser = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'john2@example.com',
            'password' => bcrypt('password')
        ]);

        $this->actingAs($anotherUser);

        $response = $this->get(route('download.video', ['id' => $this->video->idVideo]));

        $response->assertStatus(404);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You are not allowed to download this video.']);
    }

    public function testDownloadJsonVideoNotOwned()
    {
        $anotherUser = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'john2@example.com',
            'password' => bcrypt('password')
        ]);

        $this->actingAs($anotherUser);

        $response = $this->get(route('download.jsonVideo', ['id' => $this->video->idVideo]));

        $response->assertStatus(404);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You are not allowed to download this video.']);
    }

    public function testDownloadLibraryNotOwned()
    {
        $anotherUser = User::create([
            'name' => 'John2',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'john2@example.com',
            'password' => bcrypt('password')
        ]);

        $this->actingAs($anotherUser);

        $response = $this->get(route('download.library', ['id' => $this->picture->idPicture]));

        $response->assertStatus(404);
        $response->assertViewIs('error');
        $response->assertViewHas('data', ['error' => 'You are not allowed to download this library.']);
    }
}
