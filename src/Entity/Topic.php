<?php

namespace App\Entity;

use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;

#[Entity(repositoryClass: TopicRepository::class)]
#[HasLifecycleCallbacks]
class Topic extends AbstractMessage
{
    #[Column]
    private string $title;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'topics')]
    private User $author;

    /**
     * @var Collection<Post>
     */
    #[OneToMany(mappedBy: 'topic', targetEntity: Post::class)]
    private Collection $posts;

    #[ManyToOne(targetEntity: Forum::class, inversedBy: 'topics')]
    private Forum $forum;

    /**
     * @var Collection<UserTopicView>
     */
    #[OneToMany(mappedBy: 'topic', targetEntity: UserTopicView::class)]
    private Collection $userViews;

    public function __construct()
    {
        parent::__construct();
        $this->posts = new ArrayCollection();
        $this->userViews = new ArrayCollection();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Topic
    {
        $this->title = $title;
        return $this;
    }

    public function getAuthor(): User
    {
        return $this->author;
    }

    public function setAuthor(User $author): Topic
    {
        $this->author = $author;
        return $this;
    }

    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function setPosts(Collection $posts): Topic
    {
        $this->posts = $posts;
        return $this;
    }

    public function addPost(Post $post): Topic
    {
        if ($this->posts->contains($post) === false) {
            $this->posts->add($post);
        }
        $post->setTopic($this);
        return $this;
    }

    public function removePosts(Post $post): Topic
    {
        if ($this->posts->contains($post) === true) {
            $this->posts->removeElement($post);
        }
        return $this;
    }

    public function getForum(): Forum
    {
        return $this->forum;
    }

    public function setForum(Forum $forum): Topic
    {
        $this->forum = $forum;
        return $this;
    }

    public function getLastPost(): ?Post
    {
        $posts = $this->getPosts();
        $lastPost = $posts->last();
        return $lastPost instanceof Post ? $lastPost : null;
    }

    /**
     * @return Collection<UserTopicView>
     */
    public function getUserViews(): Collection
    {
        return $this->userViews;
    }

    /**
     * @param Collection<UserTopicView> $userViews
     * @return Topic
     */
    public function setUserViews(Collection $userViews): Topic
    {
        $this->userViews = $userViews;
        return $this;
    }

    public function addUserView(UserTopicView $userView): Topic
    {
        if (!$this->userViews->contains($userView)) {
            $this->userViews->add($userView);
        }
        $userView->setTopic($this);
        return $this;
    }

    public function removeUserView(UserTopicView $userView): Topic
    {
        if ($this->userViews->contains($userView)) {
            $this->userViews->removeElement($userView);
        }
        return $this;
    }
}
