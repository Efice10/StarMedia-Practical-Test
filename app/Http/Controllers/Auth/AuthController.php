<?php

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\GetAuthUser;
use App\Actions\Auth\LoginUser;
use App\Actions\Auth\LogoutUser;
use App\Actions\Auth\RefreshToken;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;

class AuthController extends Controller
{
    /**
     * Handle user login
     */
    public function login(LoginRequest $request, LoginUser $action)
    {
        return $action->execute($request->validated());
    }

    /**
     * Handle user logout
     */
    public function logout(LogoutUser $action)
    {
        return $action->execute();
    }

    /**
     * Refresh authentication token
     */
    public function refresh(RefreshToken $action)
    {
        return $action->execute();
    }

    /**
     * Get authenticated user information
     */
    public function user(GetAuthUser $action)
    {
        return $action->execute();
    }
}
