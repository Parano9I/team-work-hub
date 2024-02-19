<?php

namespace Modules\Auth\Contracts;

use Modules\Auth\DTO\Auth\Login;
use Modules\Auth\Models\Account;

interface AuthService
{
    public function login(Login $request): Account;

    public function logout(Account $account):bool;
}
