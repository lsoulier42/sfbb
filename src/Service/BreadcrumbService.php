<?php

namespace App\Service;

use App\Contract\Service\BreadcrumbServiceInterface;
use App\Dto\Homepage\BreadcrumbElement;
use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Topic;
use App\Repository\ForumRepository;
use App\Repository\PostRepository;
use App\Repository\TopicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class BreadcrumbService implements BreadcrumbServiceInterface
{
    public function __construct(
        private readonly ForumRepository $forumRepository,
        private readonly TopicRepository $topicRepository,
        private readonly PostRepository $postRepository,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {
    }

    /**
     * @inheritDoc
     */
    public function generateBreadcrumb(Request $mainRequest): Collection
    {
        $routeName = $mainRequest->attributes->get('_route');
        $routeParameters = $mainRequest->attributes->get('_route_params');
        $breadcrumbList = new ArrayCollection();
        $breadcrumbList->add($this->generateBaseElement());
        if ($this->isRouteWithForumParameter($routeName)) {
            $this->handleForum($breadcrumbList, $routeParameters['forum']);
        }
        if ($this->isRouteWithTopicParameter($routeName)) {
            $this->handleTopic($breadcrumbList, $routeParameters['topic']);
        }
        if ($this->isRouteWithPostParameter($routeName)) {
            $this->handlePost($breadcrumbList, $routeParameters['post']);
        }
        return $breadcrumbList;
    }

    private function generateBaseElement(): BreadcrumbElement
    {
        $homepageLink = $this->urlGenerator->generate('homepage');
        return new BreadcrumbElement(
            'Sfbb Forum Index',
            $homepageLink
        );
    }

    private function isRouteWithForumParameter(string $routeName): bool
    {
        return in_array(
            $routeName,
            array('forum_show', 'topic_new')
        );
    }

    private function handleForum(Collection $breadcrumbList, Forum|int $forum): void
    {
        $breadcrumbList->add($this->generateForumElement($forum));
    }

    private function generateForumElement(Forum|int $forum): BreadcrumbElement
    {
        if (!$forum instanceof Forum) {
            $forum = $this->forumRepository->find($forum);
            if (!$forum instanceof Forum) {
                throw new InvalidArgumentException('No forum with this id');
            }
        }
        return new BreadcrumbElement(
            $forum->getTitle(),
            $this->urlGenerator->generate('forum_show', ['forum' => $forum->getId()])
        );
    }

    private function isRouteWithTopicParameter(string $routeName): bool
    {
        return in_array(
            $routeName,
            array('topic_show', 'topic_edit', 'post_new')
        );
    }

    private function handleTopic(Collection $breadcrumbList, Topic|int $topic): void
    {
        if (!$topic instanceof Topic) {
            $topic = $this->topicRepository->find($topic);
            if (!$topic instanceof Topic) {
                throw new InvalidArgumentException('No topic with this id');
            }
        }
        $this->handleForum($breadcrumbList, $topic->getForum());
        $breadcrumbList->add($this->generateTopicElement($topic));
    }

    private function generateTopicElement(Topic $topic): BreadcrumbElement
    {
        return new BreadcrumbElement(
            $topic->getTitle(),
            $this->urlGenerator->generate('topic_show', ['topic' => $topic->getId()])
        );
    }

    private function isRouteWithPostParameter(string $routeName): bool
    {
        return $routeName == 'post_edit';
    }

    private function handlePost(Collection $breadcrumbList, int $postId): void
    {
        $post = $this->postRepository->find($postId);
        if (!$post instanceof Post) {
            throw new InvalidArgumentException('No post with this id');
        }
        $this->handleTopic($breadcrumbList, $post->getTopic());
    }
}
