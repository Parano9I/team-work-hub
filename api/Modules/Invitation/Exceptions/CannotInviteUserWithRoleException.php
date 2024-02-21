<?php

namespace Modules\Invitation\Exceptions;

use Exception;

class CannotInviteUserWithRoleException extends Exception
{
    protected $message = 'Cannot invite a user with super-admin role.';
}
