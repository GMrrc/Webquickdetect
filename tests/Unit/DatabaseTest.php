<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Library;
use App\Models\Picture;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class DatabaseTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $library;

    public function testUserCRUDOperations()
    {
        $user = User::create([
            'name' => 'John',
            'surname' => 'Doe',
            'dateOfBirth' => '1990-01-01',
            'role' => 'user',
            'email' => 'john@example.com',
            'password' => bcrypt('password')
        ]);

        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);

        $foundUser = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($foundUser);

        $foundUser = User::where('idUser', 1)->first();
        $this->assertNotNull($foundUser);

        $this->foundUser = $user;

        $foundUser->update(['name' => 'Jane']);
        $this->assertDatabaseHas('users', ['name' => 'Jane']);

        $foundUser->delete();
        $this->assertDatabaseMissing('users', ['email' => 'john@example.com']);
    }

    public function testLibraryCRUDOperations()
    {
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

        $foundLibrary = Library::where('name', 'Main Library')->first();
        $this->assertNotNull($foundLibrary);

        $foundLibrary->delete();
        $this->assertDatabaseMissing('library', ['name' => 'Main Library']);
    }

    public function testPictureCRUDOperations()
    {
        
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

        $picture = Picture::create([
            'title' => 'Sunset',
            'format' => 'jpg',
            'size' => 1.5,
            'path' => 'path/to/image',
            'idLibrary' => $library->idLibrary
        ]);

        $picture->save();

        $this->assertDatabaseHas('pictures', ['title' => 'Sunset']);

        $picture->delete();
        $this->assertDatabaseMissing('pictures', ['title' => 'Sunset']);
    }

    public function testVideoCRUDOperations()
    {

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

        $video = Video::create([
            'title' => 'Sample Video',
            'format' => 'mp4',
            'size' => 12000000,
            'path' => 'path/to/video',
            'data' => 'data',
            'idLibrary' => $library->idLibrary
        ]);
        $this->assertDatabaseHas('videos', ['title' => 'Sample Video']);

        $video->delete();
        $this->assertDatabaseMissing('videos', ['title' => 'Sample Video']);
    }
}
