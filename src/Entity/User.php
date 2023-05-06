<?php

namespace App\Entity;

use App\Enum\RoleEnum;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\OneToOne;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[HasLifecycleCallbacks]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(length: 180, unique: true, nullable: false)]
    private string $username;

    #[ORM\Column(unique: true, nullable: false)]
    private string $email;

    #[ORM\Column]
    private bool $isEnabled = true;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(nullable: true)]
    private ?string $firstName = null;

    #[ORM\Column(nullable: true)]
    private ?string $lastName = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $birthDate = null;

    #[ORM\Column(nullable: true)]
    private ?string $city = null;

    #[ORM\Column(nullable: true)]
    private ?string $avatarUrl = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $lastConnexion = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $lastActivity = null;

    /**
     * @var Collection<Topic>
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Topic::class)]
    private Collection $topics;

    /**
     * @var Collection<Post>
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Post::class)]
    private Collection $posts;

    /**
     * @var Collection<Forum>
     */
    #[ORM\ManyToMany(targetEntity: Forum::class)]
    private Collection $moderatedForums;

    /**
     * @var Collection<UserTopicView>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserTopicView::class)]
    private Collection $topicViews;

    /**
     * @var Collection<Collection>
     */
    #[ORM\ManyToMany(targetEntity: Chat::class)]
    private Collection $chats;

    /**
     * @var Collection<DirectMessage>
     */
    #[ORM\OneToMany(mappedBy: 'author', targetEntity: DirectMessage::class)]
    private Collection $directMessages;

    /**
     * @var Collection<UserChatView>
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: UserChatView::class)]
    private Collection $chatViews;

    public function __construct()
    {
        parent::__construct();
        $this->topics = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->moderatedForums = new ArrayCollection();
        $this->topicViews = new ArrayCollection();
        $this->chats = new ArrayCollection();
        $this->directMessages = new ArrayCollection();
        $this->chatViews = new ArrayCollection();
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): User
    {
        $this->isEnabled = $isEnabled;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): User
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): User
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getBirthDate(): ?DateTimeImmutable
    {
        return $this->birthDate;
    }

    public function setBirthDate(?DateTimeImmutable $birthDate): User
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): User
    {
        $this->city = $city;
        return $this;
    }

    public function getAvatarUrl(): ?string
    {
        return $this->avatarUrl;
    }

    public function setAvatarUrl(?string $avatarUrl): User
    {
        $this->avatarUrl = $avatarUrl;
        return $this;
    }

    public function getLastConnexion(): ?DateTimeImmutable
    {
        return $this->lastConnexion;
    }

    public function setLastConnexion(?DateTimeImmutable $lastConnexion): User
    {
        $this->lastConnexion = $lastConnexion;
        return $this;
    }

    public function getLastActivity(): ?DateTimeImmutable
    {
        return $this->lastActivity;
    }

    public function setLastActivity(?DateTimeImmutable $lastActivity): User
    {
        $this->lastActivity = $lastActivity;
        return $this;
    }

    public function getTopics(): Collection
    {
        return $this->topics;
    }

    public function setTopics(Collection $topics): User
    {
        $this->topics = $topics;
        return $this;
    }

    public function addTopic(Topic $topic): User
    {
        if ($this->topics->contains($topic) === false) {
            $this->topics->add($topic);
        }
        $topic->setAuthor($this);
        return $this;
    }

    public function removeTopic(Topic $topic): User
    {
        if ($this->topics->contains($topic) === true) {
            $this->topics->removeElement($topic);
        }
        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function setPosts(Collection $posts): User
    {
        $this->posts = $posts;
        return $this;
    }

    public function addPost(Post $post): User
    {
        if ($this->posts->contains($post) === false) {
            $this->posts->add($post);
        }
        $post->setAuthor($this);
        return $this;
    }

    public function removePosts(Post $post): User
    {
        if ($this->posts->contains($post) === true) {
            $this->posts->removeElement($post);
        }
        return $this;
    }

    public function hasRole(RoleEnum $role): bool
    {
        return in_array($role->name, $this->getRoles());
    }

    public function getMainRole(): RoleEnum
    {
        $roles = $this->getRoles();
        usort($roles, static function ($role1, $role2) {
            return RoleEnum::fromName($role2)->value - RoleEnum::fromName($role1)->value;
        });
        return RoleEnum::fromName((string)current($roles));
    }

    public function getRoleTitle(): string
    {
        $role = $this->getMainRole();
        return $role->getTransKey();
    }

    public function getTotalTopics(): int
    {
        return $this->getTopics()->count();
    }

    public function getTotalPosts(): int
    {
        return $this->getPosts()->count();
    }

    public function getTotalMessages(): int
    {
        return $this->getTotalPosts() + $this->getTotalTopics();
    }

    /**
     * @return Collection<Forum>
     */
    public function getModeratedForums(): Collection
    {
        return $this->moderatedForums;
    }

    /**
     * @param Collection<Forum> $moderatedForums
     * @return User
     */
    public function setModeratedForums(Collection $moderatedForums): User
    {
        $this->moderatedForums = $moderatedForums;
        return $this;
    }

    public function addModeratedForum(Forum $forum): User
    {
        if (!$this->moderatedForums->contains($forum)) {
            $this->moderatedForums->add($forum);
        }
        $forum->addModerator($this);
        return $this;
    }

    public function removeModeratedForum(Forum $forum): User
    {
        if ($this->moderatedForums->contains($forum)) {
            $this->moderatedForums->removeElement($forum);
        }
        $forum->removeModerator($this);
        return $this;
    }

    /**
     * @return Collection<UserTopicView>
     */
    public function getTopicViews(): Collection
    {
        return $this->topicViews;
    }

    /**
     * @param Collection<UserTopicView> $topicViews
     * @return $this
     */
    public function setTopicViews(Collection $topicViews): User
    {
        $this->topicViews = $topicViews;
        return $this;
    }

    public function addTopicView(UserTopicView $topicView): User
    {
        if (!$this->topicViews->contains($topicView)) {
            $this->topicViews->add($topicView);
        }
        $topicView->setUser($this);
        return $this;
    }

    public function removeTopicView(UserTopicView $topicView): User
    {
        if ($this->topicViews->contains($topicView)) {
            $this->topicViews->removeElement($topicView);
        }
        return $this;
    }

    /**
     * @return Collection<Chat>
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    /**
     * @param Collection<Chat> $chats
     * @return User
     */
    public function setChats(Collection $chats): User
    {
        $this->chats = $chats;
        return $this;
    }

    public function addChat(Chat $chat): User
    {
        if (!$this->chats->contains($chat)) {
            $this->chats->add($chat);
        }
        $chat->addParticipant($this);
        return $this;
    }

    public function removeChat(Chat $chat): User
    {
        if ($this->chats->contains($chat)) {
            $this->chats->removeElement($chat);
        }
        $chat->removeParticipant($this);
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
     * @return User
     */
    public function setDirectMessages(Collection $directMessages): User
    {
        $this->directMessages = $directMessages;
        return $this;
    }

    public function addDirectMessage(DirectMessage $directMessage): User
    {
        if (!$this->directMessages->contains($directMessage)) {
            $this->directMessages->add($directMessage);
        }
        $directMessage->setAuthor($this);
        return $this;
    }

    public function removeDirectMessage(DirectMessage $directMessage): User
    {
        if ($this->directMessages->contains($directMessage)) {
            $this->directMessages->removeElement($directMessage);
        }
        return $this;
    }

    /**
     * @return Collection<UserChatView>
     */
    public function getChatViews(): Collection
    {
        return $this->chatViews;
    }

    /**
     * @param Collection<UserChatView> $chatViews
     * @return User
     */
    public function setChatViews(Collection $chatViews): User
    {
        $this->chatViews = $chatViews;
        return $this;
    }

    public function addChatView(UserChatView $chatView): User
    {
        if (!$this->chatViews->contains($chatView)) {
            $this->chatViews->add($chatView);
        }
        $chatView->setUser($this);
        return $this;
    }

    public function removeChatView(UserChatView $chatView): User
    {
        if ($this->chatViews->contains($chatView)) {
            $this->chatViews->removeElement($chatView);
        }
        return $this;
    }
}
