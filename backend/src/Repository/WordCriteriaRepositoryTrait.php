<?php

namespace App\Repository;

use App\Specification\Sort;
use App\Specification\WordCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;

trait WordCriteriaRepositoryTrait
{
    protected function applyWordCriteria(QueryBuilder $qb, WordCriteria $criteria, Sort $sort = Sort::RANDOM, string $wordAlias = 'word'): void
    {
        $qb ->andWhere("{$wordAlias}.lang = :lang")
            ->setParameter('lang', $criteria->getLang());

        foreach ($criteria->getEnumCriteria() as $i => $enumCriteria) {
            if($enumCriteria->shouldApply()) {
                $qb->andWhere($enumCriteria->getField()." IN (:values{$i})")
                    ->setParameter("values{$i}", $enumCriteria->getAllowedValues());
            }
        }

        if (count($criteria->getExclusions())) {
            $qb->andWhere("{$wordAlias}.id NOT IN (:exclusions)")
                ->setParameter('exclusions', $criteria->getExclusions());
        }

        if ($sort === Sort::RANDOM) {
            $qb2 = clone $qb;
            $qb2->select("MIN({$wordAlias}.id) AS min, MAX({$wordAlias}.id) AS max");
            $result = $qb2->getQuery()->getSingleResult();
            $randomPossibleId = rand($result['min'], $result['max']);
            $qb->andWhere('word.id >= :random_id')
                ->setParameter('random_id', $randomPossibleId);
        }

        $qb->setMaxResults(1);
    }

}
