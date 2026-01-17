<?php

namespace App\Specification;

use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;
use App\Exception\NoWordFoundException;
use Random\RandomException;

/**
 * Trait allowing WordCriteria to be applied on a QueryBuilder.
 *
 * @author Wilhelm Zwertvaegher
 */
class WordCriteriaService
{
    public function __construct(private readonly CriterionConverter $criterionConverter)
    {
    }

    /**
     * @throws NoWordFoundException
     * @throws RandomException
     */
    public function applyWordCriteria(QueryBuilderInterface $qb, WordCriteria $criteria, Sort $sort = Sort::RANDOM, ?EntitiesAliases $aliases = null): void
    {
        if (null === $aliases) {
            $aliases = new EntitiesAliases(Word::class, 'word', Subject::class, 's', Qualifier::class, 'q');
        }

        $qb->andWhere($aliases->getAlias(Word::class).'.lang = :lang')
            ->setParameter('lang', $criteria->getLang());

        $this->criterionConverter->applyAll($qb, $criteria->getCriteria(), $aliases);

        if (Sort::RANDOM === $sort) {
            $count = $qb->count($aliases->getAlias(Word::class).'.id');

            if ($count > 0) {
                $offset = random_int(0, $count - 1);
                $qb->setFirstResult($offset);
            } else {
                throw new NoWordFoundException('No word found.');
            }
        }

        $qb->setMaxResults(1);
    }
}
