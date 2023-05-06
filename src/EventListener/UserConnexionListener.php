<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class UserConnexionListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly UserRepository $userRepository
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => ['onLoginSuccess']
        ];
    }

    public function onLoginSuccess(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();
        if (!$user instanceof User) {
            return;
        }
        $now = new DateTimeImmutable();
        $user->setLastConnexion($now);
        $this->userRepository->createOrUpdate($user);
    }
}
