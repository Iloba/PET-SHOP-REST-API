<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\PublicHelper;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAnAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $publicHelper = new PublicHelper();
        $token =   $publicHelper->GetAndDecodeJWT();
        $user =   $publicHelper->getAuthenticatedUser($token);
        if(!$user->is_admin){    
          abort(403, "Unauthorized Action");
        }
        return $next($request);
    }
}
