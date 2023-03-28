<?php

namespace Tests;

use App\Models\User;
use App\Handlers\Jwt\AuthHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;
    public $user;
    public $token;
    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->token =  $token = $this->respondWithToken($this->user);
    }

    protected function respondWithToken($user)
    {
        $authHandler = new AuthHandler;
        $token = $authHandler->GenerateToken($user);
        return $token;
    }
}
