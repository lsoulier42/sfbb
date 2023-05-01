<?php

namespace App\Dto\Pager;

class PagerDto
{
    public function __construct(
        private int $currentPage = 1,
        private int $itemsPerPage = 10
    ) {
    }

    /**
     * @return int
     */
    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    /**
     * @param int $currentPage
     * @return PagerDto
     */
    public function setCurrentPage(int $currentPage): PagerDto
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    /**
     * @return int
     */
    public function getItemsPerPage(): int
    {
        return $this->itemsPerPage;
    }

    /**
     * @param int $itemsPerPage
     * @return PagerDto
     */
    public function setItemsPerPage(int $itemsPerPage): PagerDto
    {
        $this->itemsPerPage = $itemsPerPage;
        return $this;
    }
}
