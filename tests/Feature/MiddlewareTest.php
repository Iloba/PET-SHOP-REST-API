<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use DatabaseMigrations;
   
    public function test_that_user_cannot_access_protected_routes_without_token(): void
    {
        $response = $this->actingAs($this->user)->getJson(route('user.profile'));

        $response->assertStatus(401);
    }
}
