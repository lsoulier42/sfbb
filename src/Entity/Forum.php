<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToMany;
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
    private int $orderNumber;

    #[ManyToOne(targetEntity: Category::class, inversedBy: 'forums')]
    private Category $category;

    /**
     * @var Collection<Topic>
     */
    #[OneToMany(mappedBy: 'forum', targetEntity: Topic::class)]
    private Collection $topics;

    /**
     * @var Collection<User>
     */
    #[ManyToMany(targetEntity: User::class)]
    private Collection $moderators;

    public function __construct()
    {
        parent::__construct();
        $this->topics = new ArrayCollection();
        $this->moderators = new ArrayCollection();
    }

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

    public function getOrderNumber(): int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): Forum
    {
        $this->orderNumber = $orderNumber;
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

    public function getNbTopics(): int
    {
        return $this->getTopics()->count();
    }

    public function getNbMessages(): int
    {
        $nbMessages = $this->getNbTopics();
        $topics = $this->getTopics()->toArray();
        array_walk($topics, static function ($topic) use (&$nbMessages) {
            $nbMessages += (int)($topic->getPosts()->count());
        });
        return $nbMessages;
    }

    /**
     * @return Collection<User>
     */
    public function getModerators(): Collection
    {
        return $this->moderators;
    }

    /**
     * @param Collection<User> $moderators
     * @return Forum
     */
    public function setModerators(Collection $moderators): Forum
    {
        $this->moderators = $moderators;
        return $this;
    }

    public function addModerator(User $moderator): Forum
    {
        if (!$this->moderators->contains($moderator)) {
            $this->moderators->add($moderator);
        }
        $moderator->addModeratedForum($this);
        return $this;
    }

    public function removeModerator(User $moderator): Forum
    {
        if ($this->moderators->contains($moderator)) {
            $this->moderators->removeElement($moderator);
        }
        $moderator->removeModeratedForum($this);
        return $this;
    }
}
