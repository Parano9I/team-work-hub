<?php

namespace TeamWorkHub\Modules\Invitation\Tests\Unit\Services;

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
use TeamWorkHub\Modules\Invitation\Models\Invitation;

class InvitationServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_activationUrl_of_admin_invitation()
    {
        $invitation = Invitation::factory()->make();


    }
}
