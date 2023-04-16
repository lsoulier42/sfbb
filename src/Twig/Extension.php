<?php

namespace App\Twig;

use App\Entity\User;
use App\Enum\RoleEnum;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_admin', [$this, 'isAdmin']),
        ];
    }

    public function isAdmin(User $user): bool
    {
        return $this->authorizationChecker->isGranted(RoleEnum::ROLE_ADMIN->name, $user);
    }
}
