<?php

namespace TeamWorkHub\Modules\Auth\Mappers;

use TeamWorkHub\Modules\Auth\DataTransferObjects\Auth\Login;

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
