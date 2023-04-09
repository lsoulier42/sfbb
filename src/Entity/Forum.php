<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: ForumRepository::class)]
#[HasLifecycleCallbacks]
class Forum extends AbstractEntity
{
    #[Column]
    private string $title;

    #[Column(nullable: true)]
    private ?string $subTitle = null;

    #[Column]
    private int $order = 0;

    #[ManyToOne(targetEntity: Category::class, inversedBy: 'forums')]
    private Category $category;

    /**
     * @var Collection<Topic>
     */
    #[OneToMany(mappedBy: 'forum', targetEntity: Topic::class)]
    private Collection $topics;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Forum
    {
        $this->title = $title;
        return $this;
    }

    public function getSubTitle(): ?string
    {
        return $this->subTitle;
    }

    public function setSubTitle(?string $subTitle): Forum
    {
        $this->subTitle = $subTitle;
        return $this;
    }

    public function getOrder(): int
    {
        return $this->order;
    }

    public function setOrder(int $order): Forum
    {
        $this->order = $order;
        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): Forum
    {
        $this->category = $category;
        return $this;
    }

    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function setTopics(Collection $topics): Forum
    {
        $this->topics = $topics;
        return $this;
    }

    public function addTopic(Topic $topic): Forum
    {
        if ($this->topics->contains($topic) === false) {
            $this->topics->add($topic);
        }
        $topic->setForum($this);
        return $this;
    }

    public function removeTopic(Topic $topic): Forum
    {
        if ($this->topics->contains($topic) === true) {
            $this->topics->removeElement($topic);
        }
        return $this;
    }
}
