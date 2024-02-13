<?php

namespace TeamWorkHub\Modules\Auth\Tests\Unit\Services;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Spatie\Permission\Models\Role;
use TeamWorkHub\Modules\Auth\Contracts\AccountService;
use TeamWorkHub\Modules\Auth\Contracts\AvatarService;
use TeamWorkHub\Modules\Auth\Contracts\RoleService;
use TeamWorkHub\Modules\Auth\DataTransferObjects\Account\Create;
use TeamWorkHub\Modules\Auth\Enums\RolesEnum;
use TeamWorkHub\Modules\Auth\Models\Account;
use TeamWorkHub\Modules\Auth\Tests\TestCase;

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
