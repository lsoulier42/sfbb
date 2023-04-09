<?php

namespace App\Dto\User;

class UserRegisterDto extends AbstractUserCreateDto
{
    private bool $agreeTerms = false;

    public function isAgreeTerms(): bool
    {
        return $this->agreeTerms;
    }

    public function setAgreeTerms(bool $agreeTerms): UserRegisterDto
    {
        $this->agreeTerms = $agreeTerms;
        return $this;
    }
}
