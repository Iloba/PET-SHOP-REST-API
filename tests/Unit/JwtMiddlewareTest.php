<?php

namespace Tests\Unit;

use App\Http\Middleware\VerifyJwt;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;

class JwtMiddlewareTest extends TestCase
{
    public function testMiddlewareThrowsExceptionWhenAuthorizationHeaderNotPresent()
    {
        // $request = new Request();
        // $middleware = new VerifyJwt();

        // $this->expectException(AuthenticationException::class);
        // $this->expectExceptionMessage('authorization header not found');

        // $middleware->handle($request, function($request){
        //     $request->token
        // });
    }

    public function testMiddlewareDoesNotThrowExceptionWhenAuthorizationHeaderPresent()
    {
       
    }
}
