<?php

namespace App\Service;

use App\Contract\Service\UserServiceInterface;
use App\Dto\Pager\PagerDto;
use App\Dto\User\AbstractUserCreateDto;
use App\Dto\User\MemberFilterDto;
use App\Dto\User\UserEditProfileDto;
use App\Dto\User\UserRegisterDto;
use App\Entity\User;
use App\Helper\HydratorHelper;
use App\Repository\UserRepository;
use InvalidArgumentException;
use LogicException;
use Pagerfanta\Pagerfanta;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService implements UserServiceInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function createNewUser(AbstractUserCreateDto $dto, bool $flush = true): User
    {
        $user = HydratorHelper::convertClassNamingConvention($dto, User::class);
        $user->setRoles(array($dto->getRole()->name));
        $this->userRepository->createOrUpdate($user, $flush);
        return $user;
    }

    public function getUserEditProfileDtoFromUser(User $user): UserEditProfileDto
    {
        return HydratorHelper::convertClassNamingConvention($user, UserEditProfileDto::class);
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

    public function editUserProfile(UserEditProfileDto $dto, User $user): User
    {
        $email = $dto->getEmail();
        $newPassword = $dto->getNewPassword();
        if ($email !== $user->getEmail() || $newPassword !== null) {
            $currentPassword = $dto->getCurrentPassword();
            if ($currentPassword === null) {
                throw new LogicException('user.edit_profile.error.no_current_password');
            }
            if (!$this->userPasswordHasher->isPasswordValid($user, $currentPassword)) {
                throw new InvalidArgumentException('user.edit_profile.error.invalid_current_password');
            }
        }
        HydratorHelper::updateObjectNamingConvention($dto, $user);
        if ($newPassword !== null) {
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $newPassword));
        }
        $this->userRepository->createOrUpdate($user);
        return $user;
    }
}
