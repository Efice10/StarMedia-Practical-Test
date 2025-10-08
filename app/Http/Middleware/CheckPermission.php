<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            return unauthorized('Authentication required');
        }

        $user = auth()->user();
        
        if (!$user->hasPermissionTo($permission)) {
            return forbidden(
                'You do not have permission to perform this action', 
                [
                    'required_permission' => $permission,
                    'user_permissions' => $user->getAllPermissions()->pluck('name'),
                    'user_roles' => $user->getRoleNames(),
                ]
            );
        }

        return $next($request);
    }
}