<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\ContactController;
use App\Http\Requests\UserContactRequest;
use App\Mail\UserContactMail;
use Illuminate\Support\Facades\Mail;
use Mockery;
use Exception;
use Illuminate\Http\RedirectResponse;

class ContactControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(200);
        $response->assertViewIs('contact_us');

    }

    public function testContactSuccess()
    {
        $request = Mockery::mock(UserContactRequest::class);
        $request->shouldReceive('validated')->once()->andReturn(['name' => 'John Doe', 'email' => 'johndoe@example.com', 'message' => 'Hello']);

        Mail::fake();

        $controller = new ContactController();

        $response = $controller->contact($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('Votre demande à bien été envoyé', session('success'));
    }

    public function testContactFailure()
    {
        $request = Mockery::mock(UserContactRequest::class);
        $request->shouldReceive('validated')->once()->andReturn(['name' => 'John Doe', 'email' => 'johndoe@example.com', 'message' => 'Hello']);

        Mail::shouldReceive('send')->andThrow(new Exception('SMTP Error'));

        $controller = new ContactController();

        $response = $controller->contact($request);

        $this->assertInstanceOf(RedirectResponse::class, $response);
        $this->assertEquals('Erreur lors de l\'envoi du message : SMTP Error', session('error'));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
