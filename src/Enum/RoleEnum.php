<?php

namespace App\Enum;

enum RoleEnum: int
{
    case ROLE_USER = 10;
    case ROLE_MODERATOR = 20;
    case ROLE_SUPER_MODERATOR = 30;
    case ROLE_ADMIN = 40;
}
