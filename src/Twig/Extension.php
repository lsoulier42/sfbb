<?php

namespace App\Twig;

use App\Contract\Service\AccessServiceInterface;
use App\Entity\User;
use App\Enum\RoleEnum;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class Extension extends AbstractExtension
{
    public function __construct(
        private readonly AccessServiceInterface $accessService
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('is_admin', [$this->accessService, 'isAdmin']),
            new TwigFunction('can_edit_post', [$this->accessService, 'canEditPost']),
            new TwigFunction('get_role_color_class', [$this, 'getRoleColorClass']),
        ];
    }

    public function getRoleColorClass(User $user): string
    {
        $role = $user->getMainRole();
        return $role->getClassColor();
    }
}
