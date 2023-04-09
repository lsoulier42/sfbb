<?php

namespace App\Contract\Service;

use App\Dto\User\AbstractUserCreateDto;

interface UserServiceInterface
{
    public function createNewUser(AbstractUserCreateDto $dto, bool $flush = true): void;
}
