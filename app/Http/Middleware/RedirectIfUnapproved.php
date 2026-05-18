<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfUnapproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip middleware for auth-related routes to prevent redirect loops during login/logout
        $skipRoutes = [
            'login',
            'login.store',
            'logout',
            'register',
            'register.store',
            'password.request',
            'password.email',
            'password.reset',
            'password.update',
            'password.confirm',
            'verification.notice',
            'verification.verify',
            'verification.send',
            'two-factor.login',
            'two-factor.login.store',
            'profile.edit',
            'user-password.edit',
            'appearance.edit',
            'two-factor.show',
        ];

        if (in_array($request->route()?->getName(), $skipRoutes)) {
            return $next($request);
        }

        $user = $request->user();

        // If user is authenticated and is NOT approved, redirect to the pending approval page.
        // SDO Admins don't need approval.
        if ($user && ! $user->is_approved && ! $user->hasRole('sdo_admin')) {
            // Check if user is already on the pending approval page to avoid infinite loop.
            if ($request->routeIs('pending-approval') || $request->is('pending-approval*')) {
                return $next($request);
            }

            return redirect()->route('pending-approval');
        }

        return $next($request);
    }
}
