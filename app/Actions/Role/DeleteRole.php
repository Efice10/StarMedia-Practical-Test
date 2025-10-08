<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class DeleteRole
{
    public function execute(int $roleId)
    {
        try {
            $role = Role::find($roleId);

            if (!$role) {
                return not_found('Role not found');
            }

            if ($role->users()->count() > 0) {
                return failed('Cannot delete role that has assigned users', [
                    'users_count' => $role->users()->count()
                ], false, 409);
            }

            $roleName = $role->name;
            $role->delete();

            return deleted('Role "' . $roleName . '" deleted successfully');

        } catch (\Exception $e) {
            return server_error('Failed to delete role: ' . $e->getMessage());
        }
    }
}