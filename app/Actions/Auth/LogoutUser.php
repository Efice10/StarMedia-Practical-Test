<?php

namespace App\Actions\Auth;

use App\Models\ActivityLog;

class LogoutUser
{
    /**
     * Execute the logout action
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute()
    {
        $user = auth()->user();

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'logout',
            'description' => 'User logged out successfully',
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        $user->revokeCurrentToken();

        return success('Logout successful');
    }
}
