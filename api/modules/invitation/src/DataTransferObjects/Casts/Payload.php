<?php

namespace TeamWorkHub\Modules\Invitation\DataTransferObjects\Casts;

use TeamWorkHub\Modules\Auth\Enums\RolesEnum;

readonly class Payload
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public RolesEnum $role
    ) {
    }
}
