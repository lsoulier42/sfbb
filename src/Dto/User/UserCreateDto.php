<?php

namespace App\Dto\User;

use App\Enum\RoleEnum;
use DateTimeImmutable;

class UserCreateDto
{
    public function __construct(
        private string $username,
        private string $email,
        private string $password,
        private RoleEnum $role,
        private ?string $firstName = null,
        private ?string $lastName = null,
        private ?DateTimeImmutable $birthDate = null,
        private ?string $city = null,
        private ?string $avatarUrl = null
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): UserCreateDto
    {
        $this->username = $username;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserCreateDto
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): UserCreateDto
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): RoleEnum
    {
        return $this->role;
    }

    public function setRole(RoleEnum $role): UserCreateDto
    {
        $this->role = $role;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): UserCreateDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): UserCreateDto
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTimeImmutable $birthDate): UserCreateDto
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): UserCreateDto
    {
        $this->city = $city;
        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): UserCreateDto
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }
}
