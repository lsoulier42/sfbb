<?php

namespace App\Traits;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

trait Timestampable
{
    #[Column(type: Types::DATETIME_MUTABLE, nullable: false, options: ["default" => 'CURRENT_TIMESTAMP'])]
    protected ?DateTime $createdAt = null;

    #[Column(type: Types::DATETIME_MUTABLE, nullable: false, options: ["default" => 'CURRENT_TIMESTAMP'])]
    protected ?DateTime $updatedAt = null;

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    #[PrePersist]
    #[PreUpdate]
    public function setTimes(): self
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new DateTime());
        }
        $this->setUpdatedAt(new DateTime());
        return $this;
    }
}
