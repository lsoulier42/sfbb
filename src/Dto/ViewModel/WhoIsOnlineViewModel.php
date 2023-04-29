<?php

namespace App\Dto\ViewModel;

use App\Entity\User;
use App\Enum\RoleEnum;
use Doctrine\Common\Collections\Collection;

class WhoIsOnlineViewModel
{
    private int $nbTopics;

    private int $nbRegisteredUsers;

    private ?User $lastRegisteredUser = null;

    /**
     * @var Collection<User> $usersOnline
     */
    private Collection $usersOnline;

    /**
     * @var RoleEnum[]
     */
    private array $roles;

    /**
     * @return int
     */
    public function getNbTopics(): int
    {
        return $this->nbTopics;
    }

    /**
     * @param int $nbTopics
     * @return WhoIsOnlineViewModel
     */
    public function setNbTopics(int $nbTopics): WhoIsOnlineViewModel
    {
        $this->nbTopics = $nbTopics;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbRegisteredUsers(): int
    {
        return $this->nbRegisteredUsers;
    }

    /**
     * @param int $nbRegisteredUsers
     * @return WhoIsOnlineViewModel
     */
    public function setNbRegisteredUsers(int $nbRegisteredUsers): WhoIsOnlineViewModel
    {
        $this->nbRegisteredUsers = $nbRegisteredUsers;
        return $this;
    }

    /**
     * @return User|null
     */
    public function getLastRegisteredUser(): ?User
    {
        return $this->lastRegisteredUser;
    }

    /**
     * @param User|null $lastRegisteredUser
     * @return WhoIsOnlineViewModel
     */
    public function setLastRegisteredUser(?User $lastRegisteredUser): WhoIsOnlineViewModel
    {
        $this->lastRegisteredUser = $lastRegisteredUser;
        return $this;
    }

    /**
     * @return Collection<User>
     */
    public function getUsersOnline(): Collection
    {
        return $this->usersOnline;
    }

    /**
     * @param Collection<User> $usersOnline
     * @return WhoIsOnlineViewModel
     */
    public function setUsersOnline(Collection $usersOnline): WhoIsOnlineViewModel
    {
        $this->usersOnline = $usersOnline;
        return $this;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     * @return WhoIsOnlineViewModel
     */
    public function setRoles(array $roles): WhoIsOnlineViewModel
    {
        $this->roles = $roles;
        return $this;
    }
}
