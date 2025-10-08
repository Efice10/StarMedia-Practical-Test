<?php

namespace App\Actions\Permission;

use Spatie\Permission\Models\Permission;

class DeletePermission
{
    public function execute(int $permissionId)
    {
        try {
            $permission = Permission::find($permissionId);

            if (!$permission) {
                return not_found('Permission not found');
            }

            if ($permission->roles()->count() > 0) {
                return failed('Cannot delete permission that is assigned to roles', [
                    'roles_count' => $permission->roles()->count(),
                    'roles' => $permission->roles->pluck('name')
                ], false, 409);
            }

            $permissionName = $permission->name;
            $permission->delete();

            return deleted('Permission "' . $permissionName . '" deleted successfully');

        } catch (\Exception $e) {
            return server_error('Failed to delete permission: ' . $e->getMessage());
        }
    }
}