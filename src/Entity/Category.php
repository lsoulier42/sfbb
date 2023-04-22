<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: CategoryRepository::class)]
#[HasLifecycleCallbacks]
class Category extends AbstractEntity
{
    #[Column(unique: true)]
    private string $title;

    #[Column]
    private int $orderNumber;

    /**
     * @var Collection<Forum>
     */
    #[OneToMany(mappedBy: 'category', targetEntity: Forum::class)]
    private Collection $forums;

    public function __construct()
    {
        parent::__construct();
        $this->forums = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Category
    {
        $this->title = $title;
        return $this;
    }

    public function getOrderNumber(): int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): Category
    {
        $this->orderNumber = $orderNumber;
        return $this;
    }

    public function getForums(): Collection
    {
        $criteria = Criteria::create()
            ->orderBy(['orderNumber' => Criteria::ASC]);
        return $this->forums->matching($criteria);
    }

    public function setForums(Collection $forums): Category
    {
        $this->forums = $forums;
        return $this;
    }

    public function addForum(Forum $forum): Category
    {
        if ($this->forums->contains($forum) === false) {
            $this->forums->add($forum);
        }
        $forum->setCategory($this);
        return $this;
    }

    public function removeForum(Forum $forum): Category
    {
        if ($this->forums->contains($forum) === true) {
            $this->forums->removeElement($forum);
        }
        return $this;
    }
}
