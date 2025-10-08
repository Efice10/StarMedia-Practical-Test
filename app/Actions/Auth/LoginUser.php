<?php

namespace App\Actions\Auth;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginUser
{
    /**
     * Execute the login action
     *
     * @param array $credentials
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return unauthorized('Invalid credentials');
        }

        if (!$user->isActive()) {
            return forbidden('Your account is not verified. Please verify your email address.');
        }

        $abilities = $user->getAllPermissions()->pluck('name')->toArray();
        $token = $user->createToken('auth-token', $abilities);

        $user->updateLastLogin();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'login',
            'description' => 'User logged in successfully',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return success('Login successful', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $abilities,
            ],
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}
