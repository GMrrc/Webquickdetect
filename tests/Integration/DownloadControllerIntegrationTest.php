<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Picture;
use App\Models\Library;
use App\Models\Video;

class DownloadControllerIntegrationTest extends TestCase
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
            'dateOfBirth' => '1990-01-01',
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

        $this->actingAs($this->user);

        Storage::fake('local');
        Storage::put("storage/app/images/{$this->user->idUser}/test_image.jpg", 'fake-image-content');
        Storage::put("storage/app/videos/{$this->user->idUser}/test_video.mp4", 'fake-video-content');
    }

    public function testDownloadImageFlow()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('download.image', ['id' => $this->picture->idPicture]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'image/jpeg');
        $response->assertHeader('Content-Disposition', 'attachment; filename="test_image.jpg"');
    }

    public function testDownloadVideoFlow()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('download.video', ['id' => $this->video->idVideo]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'video/mp4');
        $response->assertHeader('Content-Disposition', 'attachment; filename="test_video.mp4"');
    }

    public function testDownloadJsonImageFlow()
    {
        $response = $this->get(route('download.jsonImage', ['id' => $this->picture->idPicture]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $this->picture->title . '.json"');
    }

    public function testDownloadJsonVideoFlow()
    {
        $response = $this->get(route('download.jsonVideo', ['id' => $this->video->idVideo]));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertHeader('Content-Disposition', 'attachment; filename="' . $this->video->title . '.json"');
    }
}
