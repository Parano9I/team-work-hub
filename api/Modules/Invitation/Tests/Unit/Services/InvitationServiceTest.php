<?php

namespace Modules\Invitation\Tests\Unit\Services;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Mockery\MockInterface;
use Modules\Auth\Enums\RolesEnum;
use Modules\Auth\Models\Account;
use Modules\Auth\Tests\TestCase;
use Modules\Invitation\Contracts\InvitationService;
use Modules\Invitation\DTO\Casts\Payload;
use Modules\Invitation\DTO\InvitationCreate;
use Modules\Invitation\Exceptions\CannotInviteUserWithRoleException;
use Modules\Invitation\Models\Invitation;

class InvitationServiceTest extends TestCase
{

    protected InvitationService $invitationService;

    protected MockInterface $mailer;

    protected Str $str;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mailer = $this->partialMock(Mailer::class, function (MockInterface $mock) {
            $mock->shouldReceive('to')->andReturn($mock);
            $mock->shouldReceive('send');
        });

        $this->str = $this->app->make(Str::class);
    }

    public function test_invite_user_success()
    {
        $sender = Account::factory()->make();

        $invitation = $this->partialMock(Invitation::class, function (MockInterface $mock) {
            $mock->shouldReceive('save')->once()->andReturn(true);
            $mock->shouldReceive('where')->once()->andReturn($mock);
            $mock->shouldReceive('exists')->andReturn(false);
            $mock->shouldReceive('newInstance')->once()->andReturn(($mock));
        });

        $this->invitationService = $this->app->make(
            InvitationService::class,
            [$invitation, $this->mailer, $this->str]
        );

        $dto = new InvitationCreate(
            'test@email.com',
            'Test',
            'User',
            RolesEnum::ADMIN,
            'http://localhost/admin/invitation/activate'
        );

        $invite = $this->invitationService->invite($sender, $dto);

        $this->assertNotEmpty($invite);
    }

    public function test_cannot_invite_super_admin_user()
    {
        $sender = Account::factory()->make();

        $invitation = $this->partialMock(Invitation::class);

        $this->invitationService = $this->app->make(
            InvitationService::class,
            [$invitation, $this->mailer, $this->str]
        );

        $dto = new InvitationCreate(
            'test@email.com',
            'Test',
            'User',
            RolesEnum::SUPER_ADMIN,
            'http://localhost/admin/invitation/activate'
        );

        $this->expectException(CannotInviteUserWithRoleException::class);
        $this->expectExceptionMessage('Cannot invite a user with super-admin role.');

        $invite = $this->invitationService->invite($sender, $dto);
    }

    public function test_cannot_invite_admin_if_sender_role_is_admin()
    {
        $invitation = $this->partialMock(Invitation::class);

        $sender = $this->partialMock(Account::class, function (MockInterface $mock) {
            $mock->shouldReceive('hasRole')->once()->andReturn(true);
        });

        $this->invitationService = $this->app->make(
            InvitationService::class,
            [$invitation, $this->mailer, $this->str]
        );

        $dto = new InvitationCreate(
            'test@email.com',
            'Test',
            'User',
            RolesEnum::ADMIN,
            'http://localhost/admin/invitation/activate'
        );

        $this->expectException(CannotInviteUserWithRoleException::class);
        $this->expectExceptionMessage('Cannot invite user with admin role if you role are not super-admin.');

        $invite = $this->invitationService->invite($sender, $dto);
    }

    public function test_renew_success()
    {
        $token = $this->str->random(16);
        $expirationAt = (new Carbon())->subHours(24);

        $invitation = $this->partialMock(
            Invitation::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('save')->once();
            }
        );

        $invitation->token = $token;
        $invitation->expiration_at = $expirationAt;
        $invitation->payload = new Payload(
            'Test',
            'User',
            RolesEnum::DEVELOPER
        );
        $invitation->email = 'test_user@gmail.com';

        $invitationModel = $this->partialMock(Invitation::class, function (MockInterface $mock) {
            $mock->shouldReceive('save')->andReturn(true);
            $mock->shouldReceive('where')->andReturn($mock);
            $mock->shouldReceive('exists')->andReturn(false);
            $mock->shouldReceive('newInstance')->andReturn(($mock));
        });

        $this->invitationService = $this->app->make(
            InvitationService::class,
            [$invitationModel, $this->mailer, $this->str]
        );

        $renewedInvitation = $this->invitationService->renew($invitation, 'htttp://localhost/admin');

        $this->assertNotEquals($token, $renewedInvitation->token);

        $this->assertFalse($renewedInvitation->isExpired());
    }

}
