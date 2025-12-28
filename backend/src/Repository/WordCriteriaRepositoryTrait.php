<?php

namespace App\Repository;

use App\Specification\Sort;
use App\Specification\WordCriteria;
use Doctrine\ORM\QueryBuilder;

trait WordCriteriaRepositoryTrait
{
    protected function applyWordCriteria(QueryBuilder $qb, WordCriteria $criteria, Sort $sort = Sort::RANDOM, string $wordAlias = 'word'): void
    {
        $qb ->andWhere("{$wordAlias}.lang = :lang")
            ->setParameter('lang', $criteria->getLang());

        foreach ($criteria->getEnumCriteria() as $i => $enumCriteria) {
            if($enumCriteria->shouldApply()) {
                $qb->andWhere("{$wordAlias}.".$enumCriteria->getField()." IN (:values{$i})")
                    ->setParameter("values{$i}", $enumCriteria->getAllowedValues());
            }
        }

        if (count($criteria->getExclusions())) {
            $qb->andWhere("{$wordAlias}.id NOT IN (:exclusions)")
                ->setParameter('exclusions', $criteria->getExclusions());
        }

        if ($sort === Sort::RANDOM) {
            $qb2 = clone $qb;
            $count = (int) $qb2
                ->select("COUNT({$wordAlias}.id)")
                ->getQuery()
                ->getSingleScalarResult();
            if ($count > 0) {
                $offset = random_int(0, $count - 1);
                $qb->setFirstResult($offset);
            }
        }

        $qb->setMaxResults(1);
    }

}
