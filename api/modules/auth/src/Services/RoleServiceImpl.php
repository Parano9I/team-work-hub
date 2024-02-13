<?php

namespace TeamWorkHub\Modules\Auth\Services;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;
use TeamWorkHub\Modules\Auth\Contracts\RoleService;
use TeamWorkHub\Modules\Auth\Enums\RolesEnum;

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
