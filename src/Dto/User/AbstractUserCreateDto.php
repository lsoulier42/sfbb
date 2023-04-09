<?php

namespace App\Dto\User;

use App\Enum\RoleEnum;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

abstract class AbstractUserCreateDto
{
    #[NotBlank]
    #[Length(min: 4)]
    protected string $username;

    #[Email]
    #[NotBlank]
    protected string $email;

    #[NotBlank]
    #[Length(min: 6)]
    protected string $password;

    protected RoleEnum $role = RoleEnum::ROLE_USER;

    protected ?string $firstName = null;

    protected ?string $lastName = null;

    protected ?DateTimeImmutable $birthDate = null;

    protected ?string $city = null;

    protected ?string $avatarUrl = null;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): RoleEnum
    {
        return $this->role;
    }

    public function setRole(RoleEnum $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTimeImmutable $birthDate): self
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;
        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): self
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }
}