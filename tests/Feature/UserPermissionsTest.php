<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserPermissionsTest extends TestCase
{
   use DatabaseMigrations;
    public function test_that_user_without_token_cannot_visit_admin_routes(): void
    {
        $response = $this->actingAs($this->user)->putJson(route('admin.edit.user', $this->user->uuid));

        $response->assertStatus(401);
    }
}
