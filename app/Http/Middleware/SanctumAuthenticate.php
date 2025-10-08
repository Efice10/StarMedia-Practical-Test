<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SanctumAuthenticate
{
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        if (!auth('sanctum')->check()) {
            return unauthorized(
                'Authentication required',
                ['hint' => 'Please provide a valid Bearer token']
            );
        }

        $user = auth('sanctum')->user();

        // Check if user is still active (if you have an active status)
        // if (!$user->isActive()) {
        //     return forbidden(
        //         'Account deactivated',
        //         ['hint' => 'Your account has been deactivated']
        //     );
        // }

        // Check token abilities if needed
        $token = $user->currentAccessToken();
        if ($token && !$token->can('*')) {
            // Log token abilities for debugging
            \Illuminate\Support\Facades\Log::info('Token abilities', [
                'user_id' => $user->id,
                'token_abilities' => $token->abilities,
            ]);
        }

        return $next($request);
    }
}