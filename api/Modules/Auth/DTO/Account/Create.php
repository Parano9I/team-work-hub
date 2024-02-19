<?php

namespace Modules\Auth\DTO\Account;

use Illuminate\Http\UploadedFile;
use Modules\Auth\Enums\RolesEnum;

readonly class Create
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $nickname,
        public RolesEnum $role,
        public string $password,
        public \DateTime $dateOfBirth,
        public ?UploadedFile $avatar,
    ) {
    }
}
