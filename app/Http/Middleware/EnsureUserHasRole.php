<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  array<int, string>  ...$roles
     */
public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();
        // Default null role to 'user' for backward compatibility
        $userRole = $user?->role ?? 'user';

        if (! $user || ! in_array($userRole, $roles, true)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
