<?php

namespace App\Dto\User;

use App\Enum\RoleEnum;
use DateTimeImmutable;

class UserCreateFixturesDto extends AbstractUserCreateDto
{
    public function __construct(
        protected string $username,
        protected string $email,
        protected string $password,
        protected RoleEnum $role,
        protected ?string $firstName = null,
        protected ?string $lastName = null,
        protected ?DateTimeImmutable $birthDate = null,
        protected ?string $city = null,
        protected ?string $avatarUrl = null
    ) {
    }
}
