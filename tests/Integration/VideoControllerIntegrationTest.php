<?php

namespace Tests\Integration;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Library;
use App\Models\Video;

class VideoControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $library;
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

        $this->video = Video::create([
            'title' => 'test-video',
            'format' => 'mp4',
            'size' => 1000,
            'path' => "videos/{$this->user->idUser}/test_video.mp4",
            'idLibrary' => $this->library->idLibrary,
            'data' => 'json'
        ]);

        $this->actingAs($this->user);

        Storage::fake('local');
        Storage::put("videos/{$this->user->idUser}/test_video.mp4", 'fake-video-content');
    }

    public function testIndexWhenAuthenticated()
    {
        $response = $this->get('/video-processing');
        $response->assertStatus(200);
        $response->assertViewIs('video_processing');
    }

    public function testIndexWhenNotAuthenticated()
    {
        Auth::logout();
        $response = $this->get('/video-processing');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    public function testUploadWithValidFile()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('test_video.mp4');

        $response = $this->post('/video-processing', [
            'file' => $file,
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
        $response = $this->post('/video-processing', [
            'accuracy' => 0.9,
            'modeleVersion' => 'v1',
            'modeleTask' => 'detection',
            'score' => 0.8,
            'max_det' => 10,
            'libraryName' => 'Test Library'
        ]);

        $response->assertStatus(200);
        $response->assertViewIs('error');
        $response->assertViewHas('data.error', 'Invalid file uploaded');
    }

    public function testStreamVideoWhenAuthenticated()
    {
        Auth::login($this->user);

        $response = $this->get('/videos/' . $this->video->idVideo);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'video/mp4');
    }

    public function testStreamVideoWhenNotAuthenticated()
    {
        Auth::logout();

        $response = $this->get('/videos/' . $this->video->idVideo);
        $response->assertStatus(200);
    }

    public function testDeleteVideoWhenAuthenticated()
    {
        Storage::fake('local');
        Storage::put("videos/{$this->user->idUser}/test_video.mp4", 'fake-video-content');

        $response = $this->delete('/videos/' . $this->video->idVideo);

        $response->assertStatus(302);
        $response->assertRedirect(route('library.show', ['id' => $this->library->idLibrary]));
        $this->assertDatabaseMissing('videos', ['idVideo' => $this->video->idVideo]);
    }

    public function testDeleteVideoWhenNotAuthenticated()
    {
        Auth::logout();
        $response = $this->delete('/videos/' . $this->video->idVideo);
        $response->assertStatus(200);
    }
}
