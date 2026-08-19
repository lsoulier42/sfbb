<?php

namespace App\Dto\User;

class UserLoginDto
{
    private string $username;

    private string $password;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): UserLoginDto
    {
        $this->username = $username;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): UserLoginDto
    {
        $this->password = $password;
        return $this;
    }
}
