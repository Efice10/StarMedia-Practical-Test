<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return unauthorized('Authentication required');
        }

        $user = auth()->user();
        
        if (!$user->hasRole($role)) {
            return forbidden(
                'Access denied. Required role not found', 
                [
                    'required_role' => $role,
                    'user_roles' => $user->getRoleNames(),
                ]
            );
        }

        return $next($request);
    }
}