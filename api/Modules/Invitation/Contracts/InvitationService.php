<?php

namespace Modules\Invitation\Contracts;

use Modules\Auth\Models\Account;
use Modules\Invitation\DTO\InvitationCreate;
use Modules\Invitation\Models\Invitation;

interface InvitationService
{

    public function create(InvitationCreate $request): Invitation;

    public function getByToken(string $token): Invitation;

    public function isExistsByToken(string $token): bool;

    public function sendMail(Invitation $invitation, string $activationUrl): void;

    public function renew(Invitation $invitation): Invitation;

    public function delete(Invitation $invitation): void;
}
