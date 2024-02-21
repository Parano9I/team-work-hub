<?php

namespace Modules\Invitation\Services;

use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Modules\Auth\Enums\RolesEnum;
use Modules\Auth\Models\Account;
use Modules\Invitation\Contracts\InvitationService;
use Modules\Invitation\DTO\Casts\Payload;
use Modules\Invitation\DTO\InvitationCreate;
use Modules\Invitation\DTO\MailInvitation;
use Modules\Invitation\Exceptions\CannotInviteUserWithRoleException;
use Modules\Invitation\Mail\Invite;
use Modules\Invitation\Models\Invitation;

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
     * @throws CannotInviteUserWithRoleException
     */
    public function invite(Account $sender, InvitationCreate $request): Invitation
    {
        if (RolesEnum::SUPER_ADMIN === $request->role) {
            throw new CannotInviteUserWithRoleException('Cannot invite a user with super-admin role.');
        } else if ($sender->hasRole(RolesEnum::ADMIN) && $request->role === RolesEnum::ADMIN) {
            throw new CannotInviteUserWithRoleException('Cannot invite user with admin role if you role are not super-admin.');
        }

        $invitation = $this->create($request);
        $this->sendMail($invitation, $request->activationUrl);

        return $invitation;
    }


    private function create(InvitationCreate $request): Invitation
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

    private function sendMail(Invitation $invitation, string $activationUrl): void
    {
        $mailInvitation = new MailInvitation(
            $invitation->payload->firstName,
            $invitation->payload->lastName,
            $invitation->email,
            "{$activationUrl}?token={$invitation->token}"
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

    public function renew(Invitation $invitation, string $activationUrl): Invitation
    {
//        $invitation->expiration_at = $this->getExpirationAt();
        $invitation->token = $this->generateToken();
        $invitation->save();

        $this->sendMail($invitation, $activationUrl);

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
