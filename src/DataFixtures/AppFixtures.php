<?php

namespace App\DataFixtures;

use App\Contract\Service\UserServiceInterface;
use App\Dto\User\UserCreateDto;
use App\Dto\User\UserCreateFixturesDto;
use App\Enum\RoleEnum;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserServiceInterface $userService
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
                new UserCreateFixturesDto(
                    'louise',
                    'louise@truc.com',
                    'bidule',
                    RoleEnum::ROLE_ADMIN,
                    'louise',
                    'soulier',
                    new DateTimeImmutable('1990-05-29'),
                    'Strasbourg'
                )
        ];

        foreach ($users as $user) {
            $this->userService->createNewUser($user);
        }
    }
}
