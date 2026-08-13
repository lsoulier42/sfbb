<?php

namespace App\DataFixtures;

use App\Contract\Service\UserServiceInterface;
use App\Dto\User\UserCreateFixturesDto;
use App\Enum\RoleEnum;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Exception;
use Faker\Factory;
use Faker\Generator;

class UserFixture extends Fixture
{
    public const PASSWORD = '1234test';

    private const ADMIN_USERNAME = 'admin';
    private const SUPER_MODERATOR_USERNAME = 'super-modo';
    private const MODERATOR_USERNAME = 'modo';
    private const REGULAR_USER_COUNT = 10;

    private Generator $faker;

    public function __construct(
        private readonly UserServiceInterface $userService
    ) {
        $this->faker = Factory::create('fr_FR');
    }

    /**
     * @param ObjectManager $manager
     * @return void
     * @throws Exception
     */
    public function load(ObjectManager $manager): void
    {
        $usedUsernames = [];
        $privileged = [
            [self::ADMIN_USERNAME, RoleEnum::ROLE_ADMIN],
            [self::SUPER_MODERATOR_USERNAME, RoleEnum::ROLE_SUPER_MODERATOR],
            [self::MODERATOR_USERNAME, RoleEnum::ROLE_MODERATOR]
        ];
        foreach ($privileged as [$username, $role]) {
            $this->createUser($username, $role, '-55 years', '-25 years');
            $usedUsernames[] = $username;
        }
        for ($i = 0; $i < self::REGULAR_USER_COUNT; $i++) {
            do {
                $username = $this->faker->userName();
            } while (in_array($username, $usedUsernames, true));
            $usedUsernames[] = $username;
            $this->createUser($username, RoleEnum::ROLE_USER, '-30 years', '-18 years');
        }
        $manager->flush();
    }

    /**
     * @param string $username
     * @param RoleEnum $role
     * @param string $birthFrom
     * @param string $birthTo
     * @return void
     * @throws Exception
     */
    private function createUser(string $username, RoleEnum $role, string $birthFrom, string $birthTo): void
    {
        $dto = new UserCreateFixturesDto(
            $username,
            $this->faker->unique()->email(),
            self::PASSWORD,
            $role,
            $this->faker->firstName(),
            $this->faker->lastName(),
            DateTimeImmutable::createFromMutable($this->faker->dateTimeBetween($birthFrom, $birthTo)),
            $this->faker->city()
        );
        $this->userService->createNewUser($dto, false);
    }
}
