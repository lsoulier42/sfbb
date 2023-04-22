<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[Entity(repositoryClass: ProfileRepository::class)]
#[HasLifecycleCallbacks]
class Profile extends AbstractEntity
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

    public function setFirstName(?string $firstName): Profile
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): Profile
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTimeImmutable $birthDate): Profile
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): Profile
    {
        $this->city = $city;
        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): Profile
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Profile
    {
        $this->user = $user;
        return $this;
    }
}
