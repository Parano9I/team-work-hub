<?php

namespace Modules\Auth\Mappers;

use Modules\Auth\DTO\Auth\Login;

class LoginDtoMapper
{
    public function fromArray(array $data): Login
    {
        return new Login(
            $data['identifier'],
            $data['password']
        );
    }
}
