<?php

namespace App\Specification;

use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;

/**
 * Apply Criteria on a QueryBuilder for word, qualifier or subject retrieval.
 *
 * @author Wilhelm Zwertvaegher
 */
class WordCriteriaApplier implements WordCriteriaApplierInterface
{
    public function __construct(private readonly CriterionConverter $criterionConverter)
    {
    }

    public function applyWordCriteria(QueryBuilderInterface $qb, Criteria $criteria, Sort $sort = Sort::RANDOM, ?EntitiesAliases $aliases = null): void
    {
        if (null === $aliases) {
            $aliases = new EntitiesAliases(Word::class, 'word', Subject::class, 's', Qualifier::class, 'q');
        }

        $this->criterionConverter->applyAll($qb, $criteria->getCriteria(), $aliases);

        if (Sort::RANDOM !== $sort) {
            $qb->setMaxResults(1);

            return;
        }

        $count = $qb->count($aliases->getAlias(Word::class).'.id');
        if ($count > 0) {
            $offset = random_int(0, $count - 1);
            $qb->setFirstResult($offset);
            $qb->setMaxResults(1);
        } else {
            $qb->setFirstResult(0);
            $qb->setMaxResults(0);
        }
    }
}
