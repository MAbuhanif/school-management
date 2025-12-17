<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // If user is not logged in, let generic auth middleware handle it or proceed
        if (! $user) {
            return $next($request);
        }

        // Super Admin bypass - assuming role name 'super_admin'
        if ($user->hasRole('super_admin')) {
             return $next($request);
        }

        if (! $user->is_approved) {
             // Avoid redirect loop if already on approval notice page or logout
             if ($request->routeIs('approval.notice') || $request->routeIs('logout')) {
                 return $next($request);
             }

             return redirect()->route('approval.notice');
        }

        return $next($request);
    }
}
