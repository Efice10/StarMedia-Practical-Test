<?php

namespace App\Actions\Permission;

use Spatie\Permission\Models\Permission;

class UpdatePermission
{
    public function execute(int $permissionId, array $data)
    {
        try {
            $permission = Permission::find($permissionId);

            if (!$permission) {
                return not_found('Permission not found');
            }

            $permission->update(['name' => $data['name']]);

            return updated('Permission updated successfully', $permission);

        } catch (\Exception $e) {
            return server_error('Failed to update permission: ' . $e->getMessage());
        }
    }
}