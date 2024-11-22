<?php

namespace Tests\Feature;

use Tests\TestCase;

class MainTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('state', 'home');
    }

}
