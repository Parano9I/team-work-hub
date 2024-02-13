<?php

namespace TeamWorkHub\Modules\Auth\Services;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Auth;
use TeamWorkHub\Modules\Auth\Contracts\AuthService;
use TeamWorkHub\Modules\Auth\DataTransferObjects\Auth\Login;
use TeamWorkHub\Modules\Auth\Models\Account;

class AuthServiceImpl implements AuthService
{

    private AuthManager $authManager;

    public function __construct(AuthManager $authManager)
    {
        $this->authManager = $authManager;
    }

    /**
     * @throws AuthenticationException
     */
    public function login(Login $request): Account
    {

        $identifierTypeName = $this->getIdentifierTypeName($request->identifier);

        $credentials = [
            $identifierTypeName => $request->identifier,
            'password'          => $request->password
        ];

        $guard = $this->authManager->guard('web');

        if (!$guard->attempt($credentials)) {
            throw new AuthenticationException('Sorry! You have entered invalid credentials.');
        }

        /** @var Account $account */
        $account = $guard->user();

        return $account;
    }

    public function logout(Account $account): bool
    {
        $account->currentAccessToken()->delete();

        return true;
    }

    private function getIdentifierTypeName(string $identifier): string
    {
        return filter_var($identifier, FILTER_VALIDATE_EMAIL) ? 'email' : 'nickname';
    }

}
