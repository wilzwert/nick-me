<?php

namespace App\Specification;

use Doctrine\ORM\QueryBuilder;

/**
 * @author Wilhelm Zwertvaegher
 */
class DoctrineQueryBuilder implements QueryBuilderInterface
{
    public function __construct(private QueryBuilder $queryBuilder)
    {
    }

    public function andWhere(string $where): QueryBuilderInterface
    {
        $this->queryBuilder->andWhere($where);

        return $this;
    }

    public function setParameter(string $field, mixed $value): QueryBuilderInterface
    {
        $this->queryBuilder->setParameter($field, $value);

        return $this;
    }

    public function setFirstResult(int $firstResult): QueryBuilderInterface
    {
        $this->queryBuilder->setFirstResult($firstResult);

        return $this;
    }

    public function setMaxResults(int $maxResult): QueryBuilderInterface
    {
        $this->queryBuilder->setMaxResults($maxResult);

        return $this;
    }

    public function count(string $field): int
    {
        return (int) (clone $this->queryBuilder)
            ->select(sprintf('COUNT(%s)', $field))
            ->getQuery()
            ->getSingleScalarResult();
    }
}
