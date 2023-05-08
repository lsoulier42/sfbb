<?php

namespace App\Dto\User;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserEditProfileDto
{
    #[Email]
    #[NotBlank]
    private string $email;

    private ?string $currentPassword = null;

    private ?string $newPassword = null;

    private ?string $firstName = null;

    private ?string $lastName = null;

    private ?DateTimeImmutable $birthDate = null;

    private ?string $city = null;

    private ?string $avatarUrl = null;

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserEditProfileDto
    {
        $this->email = $email;
        return $this;
    }

    public function getCurrentPassword(): ?string
    {
        return $this->currentPassword;
    }

    public function setCurrentPassword(?string $currentPassword): UserEditProfileDto
    {
        $this->currentPassword = $currentPassword;
        return $this;
    }

    public function getNewPassword(): ?string
    {
        return $this->newPassword;
    }

    public function setNewPassword(?string $newPassword): UserEditProfileDto
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): UserEditProfileDto
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): UserEditProfileDto
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTimeImmutable $birthDate): UserEditProfileDto
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): UserEditProfileDto
    {
        $this->city = $city;
        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): UserEditProfileDto
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }
}
