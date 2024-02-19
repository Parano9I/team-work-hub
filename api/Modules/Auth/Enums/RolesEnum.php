<?php

namespace Modules\Auth\Enums;

enum RolesEnum: string
{
    case SUPER_ADMIN = 'super-admin';

    case ADMIN = 'admin';

    case DEVELOPER = 'developer';

    public function label(): string
    {
        return match ($this) {
            static::SUPER_ADMIN => 'Super admin',
            static::ADMIN => 'Admin',
            static::DEVELOPER => 'Developer',
        };
    }
}
