<?php

namespace App\Http\Controllers\User;

use App\Actions\Role\CreateRole;
use App\Actions\Role\DeleteRole;
use App\Actions\Role\GetRoles;
use App\Actions\Role\UpdateRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;

class RoleController extends Controller
{
    public function index(GetRoles $action)
    {
        return $action->execute();
    }

    public function store(StoreRoleRequest $request, CreateRole $action)
    {
        return $action->execute($request->validated());
    }

    public function update(UpdateRoleRequest $request, int $roleId, UpdateRole $action)
    {
        return $action->execute($roleId, $request->validated());
    }

    public function destroy(int $roleId, DeleteRole $action)
    {
        return $action->execute($roleId);
    }
}