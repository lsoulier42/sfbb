<?php

namespace App\Dto\User;

use App\Dto\Pager\PagerDto;
use App\Enum\RoleEnum;

class MemberFilterDto extends PagerDto
{
    private ?string $username = null;

    private ?string $name = null;

    private ?string $city = null;

    private ?RoleEnum $role = null;

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string|null $username
     * @return MemberFilterDto
     */
    public function setUsername(?string $username): MemberFilterDto
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     * @return MemberFilterDto
     */
    public function setName(?string $name): MemberFilterDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @param string|null $city
     * @return MemberFilterDto
     */
    public function setCity(?string $city): MemberFilterDto
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return RoleEnum|null
     */
    public function getRole(): ?RoleEnum
    {
        return $this->role;
    }

    /**
     * @param RoleEnum|null $role
     * @return MemberFilterDto
     */
    public function setRole(?RoleEnum $role): MemberFilterDto
    {
        $this->role = $role;
        return $this;
    }
}
