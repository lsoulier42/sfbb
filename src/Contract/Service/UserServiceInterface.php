<?php

namespace App\Contract\Service;

use App\Dto\User\AbstractUserCreateDto;
use App\Entity\User;

interface UserServiceInterface
{
    public function createNewUser(AbstractUserCreateDto $dto, bool $flush = true): User;
}
