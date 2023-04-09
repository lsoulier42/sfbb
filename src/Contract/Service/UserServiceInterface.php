<?php

namespace App\Contract\Service;

use App\Dto\User\UserCreateDto;

interface UserServiceInterface
{
    public function createNewUser(UserCreateDto $dto, bool $flush = true): void;
}
