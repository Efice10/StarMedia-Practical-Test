<?php

namespace App\Http\Controllers\User;

use App\Actions\Permission\CreatePermission;
use App\Actions\Permission\DeletePermission;
use App\Actions\Permission\GetPermissions;
use App\Actions\Permission\UpdatePermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;

class PermissionController extends Controller
{
    public function index(GetPermissions $action)
    {
        return $action->execute();
    }

    public function store(StorePermissionRequest $request, CreatePermission $action)
    {
        return $action->execute($request->validated());
    }

    public function update(UpdatePermissionRequest $request, int $permissionId, UpdatePermission $action)
    {
        return $action->execute($permissionId, $request->validated());
    }

    public function destroy(int $permissionId, DeletePermission $action)
    {
        return $action->execute($permissionId);
    }
}