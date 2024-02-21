<?php

namespace Modules\Invitation\Contracts;

use Modules\Auth\Models\Account;
use Modules\Invitation\DTO\InvitationCreate;
use Modules\Invitation\Models\Invitation;

interface InvitationService
{

    public function invite(Account $sender, InvitationCreate $request): Invitation;

    public function getByToken(string $token): Invitation;

    public function isExistsByToken(string $token): bool;

    public function renew(Invitation $invitation, string $activationUrl): Invitation;

    public function delete(Invitation $invitation): void;
}
