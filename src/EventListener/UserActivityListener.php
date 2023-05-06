<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserActivityListener implements EventSubscriberInterface
{
    // temps d'inactivité en second
    public const INACTIVITY_TIME = 300;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::RESPONSE => [
                ['processActivity', 10]
            ]
        ];
    }

    public function processActivity(ResponseEvent $responseEvent): void
    {
        if (!$responseEvent->isMainRequest()) {
            return;
        }
        $token = $this->tokenStorage->getToken();
        if ($token === null) {
            return;
        }
        $user = $token->getUser();
        if (!$user instanceof User) {
            return;
        }
        $now = new DateTimeImmutable();
        $oldActivity = $user->getLastActivity();
        if (
            $oldActivity !== null
            && ($now->getTimestamp() - $oldActivity->getTimestamp() > self::INACTIVITY_TIME)
        ) {
            $user->setLastConnexion($now);
        }
        $user->setLastActivity($now);
        $this->userRepository->createOrUpdate($user);
    }
}
