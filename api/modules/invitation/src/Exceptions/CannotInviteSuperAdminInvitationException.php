<?php

namespace TeamWorkHub\Modules\Invitation\Exceptions;

use Exception;

class CannotInviteSuperAdminInvitationException extends Exception
{
    protected $message = 'Cannot invite a user with super-admin role.';
}
