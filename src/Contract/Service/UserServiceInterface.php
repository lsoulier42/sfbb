<?php

namespace App\Contract\Service;

use App\Dto\Pager\PagerDto;
use App\Dto\User\AbstractUserCreateDto;
use App\Dto\User\MemberFilterDto;
use App\Entity\User;
use Pagerfanta\Pagerfanta;

interface UserServiceInterface
{
    public function createNewUser(AbstractUserCreateDto $dto, bool $flush = true): User;

    /**
     * @param PagerDto $dto
     * @return Pagerfanta<User>
     */
    public function getMembersListPaginated(PagerDto $dto): Pagerfanta;

    /**
     * @param MemberFilterDto $dto
     * @return Pagerfanta<User>
     */
    public function findByFilterDtoPaginated(MemberFilterDto $dto): Pagerfanta;
}
