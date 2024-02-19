<?php

namespace Modules\Auth\Contracts;


use Modules\Auth\DTO\Account\Create;
use Modules\Auth\Models\Account;

interface AccountService
{
    public function create(Create $request):Account;
}
