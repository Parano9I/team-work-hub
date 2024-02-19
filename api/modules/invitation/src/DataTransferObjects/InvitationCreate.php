<?php

namespace TeamWorkHub\Modules\Invitation\DataTransferObjects;

use TeamWorkHub\Modules\Auth\Enums\RolesEnum;

readonly class InvitationCreate
{
    public function __construct(
        public string $email,
        public string $firstName,
        public string $lastName,
        public RolesEnum $role
    ) {
    }
}