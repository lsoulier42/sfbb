<?php

namespace App\Service;

use App\Contract\Service\UserServiceInterface;
use App\Dto\User\AbstractUserCreateDto;
use App\Dto\User\UserCreateDto;
use App\Entity\User;
use App\Entity\Profile;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly ProfileRepository $userProfileRepository
    ) {
    }

    public function createNewUser(AbstractUserCreateDto $dto, bool $flush = true): User
    {
        $user = new User();
        $user->setUsername($dto->getUsername())
            ->setPassword($dto->getPassword())
            ->setEmail($dto->getEmail())
            ->setRoles(array($dto->getRole()->name))
            ->setIsEnabled(true);
        $userProfile = new Profile();
        $userProfile->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setBirthDate($dto->getBirthDate())
            ->setCity($dto->getCity())
            ->setAvatarUrl($dto->getAvatarUrl())
            ->setUser($user);
        $user->setProfile($userProfile);
        $this->userProfileRepository->createOrUpdate($userProfile, false);
        $this->userRepository->createOrUpdate($user, $flush);
        return $user;
    }
}
