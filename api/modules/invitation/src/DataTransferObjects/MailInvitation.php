<?php

namespace TeamWorkHub\Modules\Invitation\DataTransferObjects;

readonly class MailInvitation
{
    public function __construct(
        public string $firstName,
        public string $lastName,
        public string $email,
        public string $url
    ) {
    }
}
