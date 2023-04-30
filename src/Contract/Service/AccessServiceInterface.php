<?php

namespace App\Contract\Service;

use App\Entity\AbstractMessage;
use App\Entity\User;

interface AccessServiceInterface
{
    public function canEditPost(User $user, AbstractMessage $message): bool;

    public function isAdmin(User $user): bool;
}
