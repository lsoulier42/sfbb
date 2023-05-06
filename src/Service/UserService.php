<?php

namespace App\Service;

use App\Contract\Service\UserServiceInterface;
use App\Dto\Pager\PagerDto;
use App\Dto\User\AbstractUserCreateDto;
use App\Dto\User\MemberFilterDto;
use App\Entity\User;
use App\Repository\UserRepository;
use Pagerfanta\Pagerfanta;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public function createNewUser(AbstractUserCreateDto $dto, bool $flush = true): User
    {
        $user = new User();
        $user->setUsername($dto->getUsername())
            ->setPassword($dto->getPassword())
            ->setEmail($dto->getEmail())
            ->setRoles(array($dto->getRole()->name))
            ->setIsEnabled(true)
            ->setFirstName($dto->getFirstName())
            ->setLastName($dto->getLastName())
            ->setBirthDate($dto->getBirthDate())
            ->setCity($dto->getCity())
            ->setAvatarUrl($dto->getAvatarUrl());
        $this->userRepository->createOrUpdate($user, $flush);
        return $user;
    }

    /**
     * @inheritDoc
     */
    public function getMembersListPaginated(PagerDto $dto): Pagerfanta
    {
        return $this->userRepository->findAllPaginated($dto);
    }

    /**
     * @inheritDoc
     */
    public function findByFilterDtoPaginated(MemberFilterDto $dto): Pagerfanta
    {
        return $this->userRepository->findByFilterDtoPaginated($dto);
    }
}
