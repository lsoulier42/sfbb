<?php

namespace App\Entity;

use App\Repository\UserRepository;
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

    #[OneToOne]
    private UserProfile $userProfile;

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

    public function __construct()
    {
        parent::__construct();
        $this->topics = new ArrayCollection();
        $this->posts = new ArrayCollection();
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

    public function getUserProfile(): UserProfile
    {
        return $this->userProfile;
    }

    public function setUserProfile(UserProfile $userProfile): User
    {
        $this->userProfile = $userProfile;
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
}
