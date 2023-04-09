<?php

namespace App\Service;

use App\Contract\Service\UserServiceInterface;
use App\Dto\User\UserCreateDto;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserProfileRepository $userProfileRepository
    ) {
    }

    public function createNewUser(UserCreateDto $dto, bool $flush = true): void
    {
        $user = new User();
        $user->setUsername($dto->getUsername())
            ->setPassword($dto->getPassword())
            ->setEmail($dto->getEmail())
            ->setRoles(array($dto->getRole()->name))
            ->setIsEnabled(true);
        $userProfile = new UserProfile();
        $userProfile->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setBirthDate($dto->getBirthDate())
            ->setCity($dto->getCity())
            ->setAvatarUrl($dto->getAvatarUrl())
            ->setUser($user);
        $user->setUserProfile($userProfile);
        $this->userProfileRepository->createOrUpdate($userProfile, false);
        $this->userRepository->createOrUpdate($user, $flush);
    }
}
