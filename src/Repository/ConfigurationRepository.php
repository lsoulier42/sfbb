<?php

namespace App\Repository;

use App\Entity\Configuration;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Configuration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Configuration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Configuration[]    findAll()
 * @method Configuration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConfigurationRepository extends BaseRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Configuration::class);
    }

    public function findByConfigKey(string $configKey): ?Configuration
    {
        $alias = self::ALIAS_CONFIGURATION;
        $queryBuilder = $this->createQueryBuilder($alias);
        self::addConfigKeyWhere($queryBuilder, $configKey, $alias);
        return $queryBuilder->getQuery()->getOneOrNullResult();
    }

    public static function addConfigKeyWhere(
        QueryBuilder $queryBuilder,
        string $configKey,
        string $aliasConfiguration
    ): QueryBuilder {
        $fieldName = self::CONFIG_KEY_FIELD;
        return self::addFieldAndWhere(
            $queryBuilder,
            $aliasConfiguration,
            $fieldName,
            $configKey
        );
    }
}
