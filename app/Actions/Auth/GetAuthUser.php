<?php

namespace App\Actions\Auth;

class GetAuthUser
{
    /**
     * Execute the get authenticated user action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute()
    {
        $user = auth()->user();

        return success('User retrieved successfully', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'last_login_at' => $user->last_login_at,
                'created_at' => $user->created_at,
            ],
        ]);
    }
}
