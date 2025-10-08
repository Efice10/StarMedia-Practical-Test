<?php

namespace App\Actions\Permission;

use Spatie\Permission\Models\Permission;

class CreatePermission
{
    public function execute(array $data)
    {
        try {
            $permission = Permission::create(['name' => $data['name']]);

            return created('Permission created successfully', $permission);

        } catch (\Exception $e) {
            return server_error('Failed to create permission: ' . $e->getMessage());
        }
    }
}