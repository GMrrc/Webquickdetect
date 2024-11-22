<?php

namespace Tests\Feature;

use Tests\TestCase;

class AproposControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->get(route('apropos'));
        $response->assertStatus(200);
        $response->assertViewIs('apropos');
    }
}
