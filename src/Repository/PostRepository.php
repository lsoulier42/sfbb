<?php

namespace App\Repository;

use App\Dto\Pager\PagerDto;
use App\Entity\Post;
use App\Entity\Topic;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Pagerfanta\Pagerfanta;

/**
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    /**
     * @param Topic $topic
     * @param PagerDto $dto
     * @return Pagerfanta<Post>
     */
    public function findAllPaginated(Topic $topic, PagerDto $dto): Pagerfanta
    {
        $alias = self::ALIAS_POST;
        $queryBuilder = $this->createQueryBuilder($alias);
        self::addTopicWhere($queryBuilder, $topic, $alias);
        return self::createPaginator($queryBuilder, $dto);
    }

    public static function addTopicWhere(
        QueryBuilder $queryBuilder,
        Topic $topic,
        string $aliasPost
    ): QueryBuilder {
        return self::addFieldAndWhere(
            $queryBuilder,
            $aliasPost,
            self::TOPIC_FIELD,
            $topic
        );
    }
}
