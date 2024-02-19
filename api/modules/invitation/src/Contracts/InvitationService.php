<?php

namespace TeamWorkHub\Modules\Invitation\Contracts;

use Modules\Auth\Models\Account;
use TeamWorkHub\Modules\Invitation\DataTransferObjects\InvitationCreate;
use TeamWorkHub\Modules\Invitation\Models\Invitation;

interface InvitationService
{

    public function send(Account $sender, InvitationCreate $request): Invitation;

    public function create(InvitationCreate $request): Invitation;

    public function getByToken(string $token): Invitation;

    public function activationUrl(Invitation $invitation): string;

    public function isExistsByToken(string $token): bool;

    public function sendMail(Invitation $invitation): void;

    public function renew(Invitation $invitation): Invitation;

    public function delete(Invitation $invitation): void;
}
