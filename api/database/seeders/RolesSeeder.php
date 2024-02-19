<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use TeamWorkHub\Modules\Auth\Enums\PermissionsEnum;
use TeamWorkHub\Modules\Auth\Enums\RolesEnum;

class RolesSeeder extends Seeder
{

    private array $data = [
        RolesEnum::ADMIN->value => [
            PermissionsEnum::LIST_ROLES,
            PermissionsEnum::INVITATION_CREATE
        ]
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        foreach ($this->data as $role => $permissions) {
            $role = Role::updateOrCreate(['name' => $role]);

            foreach ($permissions as $permission) {
                Permission::updateOrCreate(['name' => $permission->value]);
                $role->givePermissionTo($permission->value);
            }
        }

        $role = Role::create(['name' => RolesEnum::SUPER_ADMIN]);
        $role->givePermissionTo(Permission::all());
    }
}
