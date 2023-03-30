<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AppBootTest extends TestCase
{
    use DatabaseMigrations;
    public function test_that_app_can_run(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
