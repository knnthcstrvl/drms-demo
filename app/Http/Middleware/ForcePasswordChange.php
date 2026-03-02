<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePasswordChange
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->must_change_password) {
            return $next($request);
        }

        if (
            $request->routeIs('dashboard') ||
            $request->routeIs('json_data_feed') ||
            $request->routeIs('force-password-change.update') ||
            $request->routeIs('logout')
        ) {
            return $next($request);
        }

        return redirect()
            ->route('dashboard')
            ->with('force_password_change_notice', 'Please update your password before continuing.');
    }
}
