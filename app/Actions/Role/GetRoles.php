<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class GetRoles
{
    public function execute()
    {
        try {
            $roles = Role::with('permissions')->get();

            return success('Roles retrieved successfully', $roles);

        } catch (\Exception $e) {
            return server_error('Failed to retrieve roles');
        }
    }
}