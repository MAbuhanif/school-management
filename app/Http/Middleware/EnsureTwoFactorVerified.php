<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTwoFactorVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->google2fa_secret && ! $request->session()->has('auth.2fa_verified')) {
             if ($request->is('two-factor-challenge') || $request->is('logout')) {
                 return $next($request);
             }
             return redirect()->route('two-factor.login');
        }

        return $next($request);
    }
}
