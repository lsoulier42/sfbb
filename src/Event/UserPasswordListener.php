<?php

namespace App\Event;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
class UserPasswordListener
{
    public function __construct(
        private readonly UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public function prePersist(PrePersistEventArgs $eventArgs): void
    {
        $entity = $eventArgs->getObject();
        if (!$entity instanceof User) {
            return;
        }
        $password = $entity->getPassword();
        if ($password !== null) {
            $hashedPassword = $this->userPasswordHasher->hashPassword($entity, $password);
            $entity->setPassword($hashedPassword);
        }
    }
}
