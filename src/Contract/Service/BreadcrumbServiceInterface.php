<?php

namespace App\Contract\Service;

use App\Dto\Homepage\BreadcrumbElement;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;

interface BreadcrumbServiceInterface
{
    /**
     * @param Request $mainRequest
     * @return Collection<BreadcrumbElement>
     */
    public function generateBreadcrumb(Request $mainRequest): Collection;
}
