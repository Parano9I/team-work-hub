<?php

namespace Modules\Auth\Tests\Unit\Services;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Modules\Auth\Contracts\AccountService;
use Modules\Auth\Contracts\AvatarService;
use Modules\Auth\Contracts\RoleService;
use Modules\Auth\DTO\Account\Create;
use Modules\Auth\Enums\RolesEnum;
use Modules\Auth\Models\Account;
use Modules\Auth\Tests\TestCase;
use Spatie\Permission\Models\Role;

class AccountServiceTest extends TestCase
{

    private AccountService $accountService;

    private Hasher $hasher;

    protected function setUp(): void
    {
        parent::setUp();

        $this->hasher = $this->app->make(Hasher::class);
        $avatarService = $this->partialMock(AvatarService::class, function (MockInterface $mock) {
            $mock->shouldReceive('save')->andReturn('avatar.png');
        });
        $roleService = $this->partialMock(RoleService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getByName')->once()->andReturn(
                (new Role(['id' => 1, 'name' => RolesEnum::DEVELOPER]))
            );
        });
        $account = $this->partialMock(Account::class, function (MockInterface $mock) {
            $mock->shouldReceive('assignRole');
            $mock->shouldReceive('save')->andReturn(true);
            $mock->shouldReceive('newInstance')->once()->andReturn(($mock));
        });

        $this->accountService = $this->app->make(
            AccountService::class,
            [$this->hasher, $avatarService, $roleService, $account]
        );
    }

    public function test_create_account()
    {
        $createAccountRequest = new Create(
            'First',
            'Last',
            'first_name@gmail.com',
            'first_name123',
            RolesEnum::DEVELOPER,
            'password123',
            new \DateTime(),
            UploadedFile::fake()->image('testImage.png')
        );

        $account = $this->accountService->create($createAccountRequest);

        $this->assertNotEmpty($account);
        $this->assertTrue($this->hasher->check($createAccountRequest->password, $account->password));
        $this->assertEquals('avatar.png', $account->avatar);
    }
}
