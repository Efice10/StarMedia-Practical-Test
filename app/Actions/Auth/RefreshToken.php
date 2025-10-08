<?php

namespace App\Actions\Auth;

class RefreshToken
{
    /**
     * Execute the refresh token action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute()
    {
        $user = auth()->user();

        $user->revokeCurrentToken();

        $abilities = $user->getAllPermissions()->pluck('name')->toArray();
        $token = $user->createToken('auth-token', $abilities);

        return success('Token refreshed successfully', [
            'token' => $token->plainTextToken,
            'token_type' => 'Bearer',
        ]);
    }
}
