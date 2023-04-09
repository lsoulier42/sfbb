<?php

namespace App\Entity;

use App\Repository\UserProfileRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[Entity(repositoryClass: UserProfileRepository::class)]
#[HasLifecycleCallbacks]
class UserProfile extends AbstractEntity
{
    #[ORM\Column(nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $birthDate = null;

    #[ORM\Column(nullable: true)]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    private ?string $avatarUrl = null;

    #[ORM\OneToOne]
    private User $user;

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): UserProfile
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): UserProfile
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTimeImmutable $birthDate): UserProfile
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): UserProfile
    {
        $this->city = $city;
        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): UserProfile
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): UserProfile
    {
        $this->user = $user;
        return $this;
    }
}
