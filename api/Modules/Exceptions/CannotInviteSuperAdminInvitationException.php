<?php

namespace Modules\Exceptions;

use Exception;

class CannotInviteSuperAdminInvitationException extends Exception
{
    protected $message = 'Cannot invite a user with super-admin role.';
}
