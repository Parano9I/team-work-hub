<?php

namespace Modules\Auth\Enums;

enum PermissionsEnum: string
{
    case LIST_ROLES = 'roles.index';

    case INVITATION_CREATE = 'invitations.create';
}
