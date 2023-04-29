<?php

namespace App\Enum;

use InvalidArgumentException;

enum RoleEnum: int
{
    case ROLE_USER = 10;
    case ROLE_MODERATOR = 20;
    case ROLE_SUPER_MODERATOR = 30;
    case ROLE_ADMIN = 40;

    public function getTransKey(): string
    {
        return match ($this) {
            self::ROLE_USER => 'user.role.label.user',
            self::ROLE_MODERATOR => 'user.role.label.moderator',
            self::ROLE_SUPER_MODERATOR => 'user.role.label.super_moderator',
            self::ROLE_ADMIN => 'user.role.label.admin'
        };
    }

    public function getClassColor(): string
    {
        return match ($this) {
            self::ROLE_USER => 'text-success',
            self::ROLE_MODERATOR => 'text-primary',
            self::ROLE_SUPER_MODERATOR => 'text-warning',
            self::ROLE_ADMIN => 'text-danger'
        };
    }

    public static function fromName(string $name): RoleEnum
    {
        $cases = self::cases();
        foreach ($cases as $case) {
            if ($case->name === $name) {
                return $case;
            }
        }
        throw new InvalidArgumentException('Unknown role');
    }
}
