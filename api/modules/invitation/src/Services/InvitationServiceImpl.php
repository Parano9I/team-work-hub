<?php

namespace TeamWorkHub\Modules\Invitation\Services;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Auth\Enums\RolesEnum;
use Modules\Auth\Models\Account;
use Spatie\Permission\Exceptions\UnauthorizedException;
use TeamWorkHub\Modules\Invitation\Contracts\InvitationService;
use TeamWorkHub\Modules\Invitation\DataTransferObjects\Casts\Payload;
use TeamWorkHub\Modules\Invitation\DataTransferObjects\InvitationCreate;
use TeamWorkHub\Modules\Invitation\DataTransferObjects\MailInvitation;
use TeamWorkHub\Modules\Invitation\Exceptions\CannotInviteSuperAdminInvitationException;
use TeamWorkHub\Modules\Invitation\Mail\Invite;
use TeamWorkHub\Modules\Invitation\Models\Invitation;

class InvitationServiceImpl implements InvitationService
{

    private readonly Invitation $invitation;

    private readonly Mailer $mailer;

    private readonly Str $str;

    public const HOURS_UNTIL_INVITE_EXPIRATION = 24;

    public function __construct(Invitation $invitation, Mailer $mailer, Str $str)
    {
        $this->invitation = $invitation;
        $this->mailer = $mailer;
        $this->str = $str;
    }

    /**
     * @throws CannotInviteSuperAdminInvitationException
     * @throws UnauthorizedException
     */
    public function invite(Account $sender, InvitationCreate $request): Invitation
    {
        if (RolesEnum::SUPER_ADMIN === $request->role) {
            throw new CannotInviteSuperAdminInvitationException();
        } else {
            if ($sender->hasRole(RolesEnum::ADMIN) && $request->role === RolesEnum::ADMIN) {
                throw new UnauthorizedException('Cannot invite user with admin role if you role are not super-admin.');
            }
        }

        $invitation = $this->create($request);
        $this->sendMail($invitation);

        return $invitation;
    }


    public function create(InvitationCreate $request): Invitation
    {
        $payload = new Payload($request->firstName, $request->lastName, $request->role);

        $invitation = $this->invitation->newInstance();
        $invitation->email = $request->email;
        $invitation->payload = $payload;
        $invitation->expiration_at = $this->getExpirationAt();

        $token = $this->generateToken();
        $invitation->token = $token;

        $invitation->save();

        return $invitation;
    }

    public function sendMail(Invitation $invitation): void
    {
        $mailInvitation = new MailInvitation(
            $invitation->firstName,
            $invitation->lastName,
            $invitation->email,
            $this->activationUrl($invitation)
        );

        $this->mailer->to($invitation->email)->send(new Invite($mailInvitation));
    }

    private function generateToken(): string
    {
        do {
            $token = $this->str->random(16);
        } while ($this->isExistsByToken($token));

        return $token;
    }

    public function activationUrl(Invitation $invitation): string
    {
        $baseUrl = match ($invitation->payload->role->value) {
            RolesEnum::ADMIN => 'http://localhost/admin/invitation',
            RolesEnum::DEVELOPER => 'http://localhost/developer/invitation',
            default => throw new \InvalidArgumentException("Invalid role {$invitation->payload->role->value}")
        };

        return "{$baseUrl}?token={$invitation->token}";
    }

    public function isExistsByToken(string $token): bool
    {
        return $this->invitation->where('token', $token)->exists();
    }

    public function getByToken(string $token): Invitation
    {
        return $this->invitation->where('token', $token)->firstOrFail();
    }

    private function getExpirationAt(): Carbon
    {
        return (new Carbon())->addHours(self::HOURS_UNTIL_INVITE_EXPIRATION);
    }

    public function renew(Invitation $invitation): Invitation
    {
        $invitation->expiration_at = $this->getExpirationAt();
        $invitation->token = $this->generateToken();
        $invitation->save();

        $this->sendMail($invitation);

        return $invitation;
    }


    /**
     * @throws \Throwable
     */
    public function delete(Invitation $invitation): void
    {
        $invitation->deleteOrFail();
    }
}
