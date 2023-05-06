<?php

namespace App\Entity;

use App\Repository\ChatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: ChatRepository::class)]
#[HasLifecycleCallbacks]
class Chat extends AbstractEntity
{
    #[Column(type: Types::STRING)]
    private ?string $title = null;

    /**
     * @var Collection<User>
     */
    #[ManyToMany(targetEntity: User::class)]
    private Collection $participants;

    /**
     * @var Collection<DirectMessage>
     */
    #[OneToMany(mappedBy: 'chat', targetEntity: DirectMessage::class)]
    private Collection $directMessages;

    #[OneToMany(mappedBy: 'chat', targetEntity: UserChatView::class)]
    private Collection $userViews;

    public function __construct()
    {
        parent::__construct();
        $this->participants = new ArrayCollection();
        $this->directMessages = new ArrayCollection();
        $this->userViews = new ArrayCollection();
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): Chat
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return Collection<User>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    /**
     * @param Collection<User> $participants
     * @return $this
     */
    public function setParticipants(Collection $participants): Chat
    {
        $this->participants = $participants;
        return $this;
    }

    public function addParticipant(User $participant): Chat
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }
        $participant->addChat($this);
        return $this;
    }

    public function removeParticipant(User $participant): Chat
    {
        if ($this->participants->contains($participant)) {
            $this->participants->removeElement($participant);
        }
        $participant->removeChat($this);
        return $this;
    }

    /**
     * @return Collection<DirectMessage>
     */
    public function getDirectMessages(): Collection
    {
        return $this->directMessages;
    }

    /**
     * @param Collection<DirectMessage> $directMessages
     * @return Chat
     */
    public function setDirectMessages(Collection $directMessages): Chat
    {
        $this->directMessages = $directMessages;
        return $this;
    }

    public function addDirectMessage(DirectMessage $directMessage): Chat
    {
        if (!$this->directMessages->contains($directMessage)) {
            $this->directMessages->add($directMessage);
        }
        $directMessage->setChat($this);
        return $this;
    }

    public function removeDirectMessage(DirectMessage $directMessage): Chat
    {
        if ($this->directMessages->contains($directMessage)) {
            $this->directMessages->removeElement($directMessage);
        }
        return $this;
    }

    /**
     * @return Collection<UserChatView>
     */
    public function getUserViews(): Collection
    {
        return $this->userViews;
    }

    /**
     * @param Collection<UserChatView> $userViews
     * @return Chat
     */
    public function setUserViews(Collection $userViews): Chat
    {
        $this->userViews = $userViews;
        return $this;
    }

    public function addUserView(UserChatView $userView): Chat
    {
        if (!$this->userViews->contains($userView)) {
            $this->userViews->add($userView);
        }
        $userView->setChat($this);
        return $this;
    }

    public function removeUserView(UserChatView $userView): Chat
    {
        if ($this->userViews->contains($userView)) {
            $this->userViews->removeElement($userView);
        }
        return $this;
    }
}
