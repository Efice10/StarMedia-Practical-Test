<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class UpdateRole
{
    public function execute(int $roleId, array $data)
    {
        try {
            $role = Role::find($roleId);

            if (!$role) {
                return not_found('Role not found');
            }

            if (isset($data['name'])) {
                $role->update(['name' => $data['name']]);
            }

            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $role->syncPermissions($data['permissions']);
            }

            $role->load('permissions');

            return updated('Role updated successfully', $role);

        } catch (\Exception $e) {
            return server_error('Failed to update role: ' . $e->getMessage());
        }
    }
}