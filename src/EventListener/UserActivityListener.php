<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\ProfileRepository;
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
        private readonly ProfileRepository $profileRepository,
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
        $profile = $user->getProfile();
        $now = new DateTimeImmutable();
        $oldActivity = $profile->getLastActivity();
        if (
            $oldActivity !== null
            && ($now->getTimestamp() - $oldActivity->getTimestamp() > self::INACTIVITY_TIME)
        ) {
            $profile->setLastConnexion($now);
        }
        $profile->setLastActivity($now);
        $this->profileRepository->createOrUpdate($profile);
    }
}
