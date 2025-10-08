<?php

namespace App\Actions\Permission;

use Spatie\Permission\Models\Permission;

class GetPermissions
{
    public function execute()
    {
        try {
            $permissions = Permission::all()->groupBy(function ($permission) {
                $parts = explode('_', $permission->name);
                return count($parts) > 1 ? $parts[1] : 'general';
            });

            return success('Permissions retrieved successfully', $permissions);

        } catch (\Exception $e) {
            return server_error('Failed to retrieve permissions');
        }
    }
}