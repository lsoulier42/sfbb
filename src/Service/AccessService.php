<?php

namespace App\Service;

use App\Contract\Service\AccessServiceInterface;
use App\Entity\AbstractMessage;
use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AccessService implements AccessServiceInterface
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function canEditPost(User $user, AbstractMessage $message): bool
    {
        if ($this->authorizationChecker->isGranted(RoleEnum::ROLE_SUPER_MODERATOR->name, $user)) {
            return true;
        }
        return $message->getAuthor() === $user;
    }

    public function isAdmin(User $user): bool
    {
        return $this->authorizationChecker->isGranted(RoleEnum::ROLE_ADMIN->name, $user);
    }
}
