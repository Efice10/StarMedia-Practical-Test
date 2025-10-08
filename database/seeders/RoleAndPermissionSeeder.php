<?php

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach (PermissionEnum::getAllPermissions() as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        $this->createSuperAdminRole();
        $this->createAdminRole();
        $this->createUserRole();
    }

    private function createSuperAdminRole(): void
    {
        $role = Role::firstOrCreate(['name' => RoleEnum::SUPER_ADMIN->value]);
        $role->givePermissionTo(Permission::all());
    }

    private function createAdminRole(): void
    {
        $role = Role::firstOrCreate(['name' => RoleEnum::ADMIN->value]);
        $role->givePermissionTo([
            PermissionEnum::VIEW_ANALYTICS->value,
            PermissionEnum::EXPORT_ANALYTICS->value,
            PermissionEnum::VIEW_PLATFORMS->value,
            PermissionEnum::VIEW_USERS->value,
            PermissionEnum::VIEW_ROLES->value,
            PermissionEnum::VIEW_ACTIVITY_LOGS->value,
        ]);
    }

    private function createUserRole(): void
    {
        $role = Role::firstOrCreate(['name' => RoleEnum::USER->value]);
        $role->givePermissionTo([
            PermissionEnum::VIEW_ANALYTICS->value,
        ]);
    }
}