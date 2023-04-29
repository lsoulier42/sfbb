<?php

namespace App\Repository;

use App\Entity\Forum;
use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

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
        $queryBuilder
            ->addSelect("
                (CASE
                    WHEN $aliasPost.id IS NOT NULL THEN $aliasPost.$createdAtField
                    ELSE $aliasTopic.$createdAtField
                END) AS last_message_date
            ");
        $queryBuilder->addOrderBy(
            "last_message_date",
            Criteria::DESC
        );
        $results = $queryBuilder->getQuery()->getResult();
        return new ArrayCollection(array_map(static function ($el) {
            return $el[0];
        }, $results));
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
