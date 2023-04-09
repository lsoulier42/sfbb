<?php

namespace App\Entity;

use App\Traits\Timestampable;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;

#[HasLifecycleCallbacks]
class AbstractEntity
{
    use Timestampable;

    #[Column(type: Types::INTEGER, nullable: false)]
    #[Id]
    #[GeneratedValue]
    protected ?int $id = null;

    public function __construct()
    {
        $this->setCreatedAt(new DateTime());
        $this->setUpdatedAt(new DateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }
}
