<?php

namespace TeamWorkHub\Modules\Auth\DataTransferObjects\Auth;

readonly class Login
{
    public function __construct(
        public string $identifier,
        public string $password
    )
    {
    }
}
