<?php

namespace TeamWorkHub\Modules\Auth\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Spatie\Permission\Models\Role;
use TeamWorkHub\Modules\Auth\Enums\RolesEnum;

interface RoleService
{
    public function all(): Collection;

    public function getByName(RolesEnum $role):Role;
}
