<?php

namespace Modules\Auth\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Modules\Auth\Enums\RolesEnum;
use Spatie\Permission\Models\Role;

interface RoleService
{
    public function all(): Collection;

    public function getByName(RolesEnum $role):Role;
}
