<?php

namespace App\Service;

use App\Contract\Service\HomepageServiceInterface;
use App\Dto\ViewModel\WhoIsOnlineViewModel;
use App\Enum\RoleEnum;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;

class HomepageService implements HomepageServiceInterface
{
    public function __construct(
        private readonly TopicRepository $topicRepository,
        private readonly UserRepository $userRepository
    ) {
    }

    public function getWhoIsOnlineVm(): WhoIsOnlineViewModel
    {
        $vm = new WhoIsOnlineViewModel();
        $vm->setNbTopics($this->topicRepository->getTotalNbTopics())
            ->setUsersOnline($this->userRepository->findByOnline())
            ->setLastRegisteredUser($this->userRepository->findOneByLastRegistered())
            ->setNbRegisteredUsers($this->userRepository->getNbRegisteredUsers())
            ->setRoles(RoleEnum::cases())
        ;
        return $vm;
    }
}