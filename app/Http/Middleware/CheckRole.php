<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = auth('staff')->user();
        
        if (!$user) {
            abort(403, 'Unauthorized');
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Access denied. You do not have permission to access this page.');
        }

        return $next($request);
    }
}

