<?php

namespace TeamWorkHub\Modules\Auth\Services;

use Illuminate\Contracts\Hashing\Hasher;
use TeamWorkHub\Modules\Auth\Contracts\AccountService;
use TeamWorkHub\Modules\Auth\Contracts\AvatarService;
use TeamWorkHub\Modules\Auth\Contracts\RoleService;
use TeamWorkHub\Modules\Auth\DataTransferObjects\Account\Create;
use TeamWorkHub\Modules\Auth\Models\Account;

class AccountServiceImpl implements AccountService
{

    private Account $account;

    private Hasher $hasher;

    private AvatarService $avatarService;

    private RoleService $roleService;

    public function __construct(
        Hasher        $hasher,
        AvatarService $avatarService,
        RoleService   $roleService,
        Account       $account
    )
    {
        $this->avatarService = $avatarService;
        $this->hasher = $hasher;
        $this->roleService = $roleService;
        $this->account = $account;
    }

    public function create(Create $request): Account
    {
        $account = $this->account->newInstance();
        $account->first_name = $request->firstName;
        $account->last_name = $request->lastName;
        $account->nickname = $request->nickname;
        $account->email = $request->email;
        $account->password = $this->hasher->make($request->password);
        $account->date_of_birth = $request->dateOfBirth;

        if (!is_null($request->avatar)) {
            $account->avatar = $this->avatarService->save($request->avatar);
        }

        $role = $this->roleService->getByName($request->role);

        $account->assignRole($role);
        $account->save();

        return $account;
    }
}
