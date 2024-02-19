<?php

namespace Modules\Invitation\DTO\Casts;

use Modules\Auth\Enums\RolesEnum;

readonly class Payload
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public RolesEnum $role
    ) {
    }
}
