<?php

namespace Modules\Auth\DTO\Auth;

readonly class Login
{
    public function __construct(
        public string $identifier,
        public string $password
    )
    {
    }
}
