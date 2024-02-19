<?php

namespace Modules\Auth\Services;

use Illuminate\Database\Eloquent\Collection;
use Modules\Auth\Contracts\RoleService;
use Modules\Auth\Enums\RolesEnum;
use Spatie\Permission\Models\Role;

class RoleServiceImpl implements RoleService
{

    private Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function all(): Collection
    {
        return $this->role->all();
    }

    public function getByName(RolesEnum $role): Role
    {
        return $this->role->findByName($role->value);
    }
}
