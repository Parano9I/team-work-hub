<?php

namespace TeamWorkHub\Modules\Auth\Contracts;


use TeamWorkHub\Modules\Auth\DataTransferObjects\Account\Create;
use TeamWorkHub\Modules\Auth\Models\Account;

interface AccountService
{
    public function create(Create $request):Account;
}
