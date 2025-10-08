<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class CreateRole
{
    public function execute(array $data)
    {
        try {
            $role = Role::create(['name' => $data['name']]);

            if (isset($data['permissions']) && is_array($data['permissions'])) {
                $role->givePermissionTo($data['permissions']);
            }

            $role->load('permissions');

            return created('Role created successfully', $role);

        } catch (\Exception $e) {
            return server_error('Failed to create role: ' . $e->getMessage());
        }
    }
}