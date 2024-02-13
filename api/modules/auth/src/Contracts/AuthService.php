<?php

namespace TeamWorkHub\Modules\Auth\Contracts;

use TeamWorkHub\Modules\Auth\DataTransferObjects\Auth\Login;
use TeamWorkHub\Modules\Auth\Models\Account;

interface AuthService
{
    public function login(Login $request): Account;

    public function logout(Account $account):bool;
}
