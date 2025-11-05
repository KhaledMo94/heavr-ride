<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class DoesntHasRolesMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , ...$args): Response
    {
        $user = auth()->guard('sanctum')->user() ?? Auth::user();
        if (!$user || $user->hasAnyRole($args)){
            return response()->json([
                'message'                   =>__('unauthorized : only riders'),
            ],401);
        }
        return $next($request);
    }
}
