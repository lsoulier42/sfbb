<?php

namespace App\Repository;

use App\Dto\Pager\PagerDto;
use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

/**
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Topic::class);
    }

    /**
     * @param Forum $forum
     * @return Collection<Topic>
     */
    public function findByLatestPost(Forum $forum): Collection
    {
        return self::getCollectionFromQueryBuilder($this->findByLatestPostQueryBuilder($forum));
    }

    /**
     * @param Forum $forum
     * @param PagerDto $dto
     * @return Pagerfanta<Topic>
     */
    public function findByLatestPostPaginated(Forum $forum, PagerDto $dto): Pagerfanta
    {
        return self::createPaginator($this->findByLatestPostQueryBuilder($forum), $dto);
    }

    private function findByLatestPostQueryBuilder(Forum $forum): QueryBuilder
    {
        $aliasTopic = self::ALIAS_TOPIC;
        $aliasPost = self::ALIAS_POST;
        $subAliasPost = $aliasPost . '_2';
        $queryBuilder = $this->createQueryBuilder($aliasTopic);
        self::addForumWhere($queryBuilder, $forum, $aliasTopic);
        self::addPostJoin($queryBuilder, $aliasTopic, $aliasPost, Join::LEFT_JOIN);
        $subQueryBuilder = $this->getEntityManager()->createQueryBuilder();
        $createdAtField = self::CREATED_AT_FIELD;
        $subQueryBuilder->select("MAX($subAliasPost.$createdAtField)")
            ->from(Post::class, $subAliasPost)
            ->where("$subAliasPost.topic = $aliasTopic");
        $queryBuilder->andWhere("($aliasPost.createdAt = (" .
            $subQueryBuilder->getQuery()->getDQL() . ") OR $aliasPost.id IS NULL)");
        return $queryBuilder->addOrderBy(
            "CASE
                    WHEN $aliasPost.id IS NOT NULL THEN $aliasPost.$createdAtField
                    ELSE $aliasTopic.$createdAtField
                END",
            Criteria::DESC
        );
    }

    public static function addForumWhere(
        QueryBuilder $queryBuilder,
        Forum $forum,
        string $aliasTopic
    ): QueryBuilder {
        return self::addFieldAndWhere(
            $queryBuilder,
            $aliasTopic,
            self::FORUM_FIELD,
            $forum
        );
    }

    public static function addPostJoin(
        QueryBuilder $queryBuilder,
        string $aliasTopic,
        string $aliasPost,
        string $joinType
    ): QueryBuilder {
        return self::addTableJoin(
            $queryBuilder,
            $aliasTopic,
            'posts',
            $aliasPost,
            $joinType
        );
    }

    public function getTotalNbTopics(): int
    {
        $alias = self::ALIAS_TOPIC;
        $queryBuilder = $this->createQueryBuilder($alias);
        $queryBuilder->select("COUNT($alias.id) AS nb_topics");
        return $queryBuilder->getQuery()->getResult()[0]['nb_topics'];
    }
}
