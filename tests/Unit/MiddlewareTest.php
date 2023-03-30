<?php

namespace Tests\Unit;

use App\Helpers\PublicHelper;
use PHPUnit\Framework\TestCase;
use App\Http\Middleware\VerifyJwt;
use Illuminate\Auth\AuthenticationException;

class MiddlewareTest extends TestCase
{
   
    public function test_that_user_cannot_access_protected_routes_without_token(): void
    {
        $middleware = new PublicHelper;

        try {
            $middleware->GetRawJWT();
        } catch (\Exception $exception) {
            $this->assertEquals($exception->getMessage(), 'authorization header not found');
        }
    }
}
