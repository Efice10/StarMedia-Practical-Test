<?php

namespace App\Actions\User;

use App\Enums\RoleEnum;
use App\Models\User;

class AssignRole
{
    public function execute(int $userId, string $roleName)
    {
        try {
            $user = User::find($userId);

            if (!$user) {
                return not_found('User not found');
            }

            if (!in_array($roleName, RoleEnum::getAllRoles())) {
                return validation_failed('Invalid role', [
                    'role' => ['The selected role is invalid.']
                ]);
            }

            $user->syncRoles([$roleName]);

            return success('Role assigned successfully', [
                'user' => $user->name,
                'role' => $roleName,
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ]);

        } catch (\Exception $e) {
            return server_error('Failed to assign role');
        }
    }
}