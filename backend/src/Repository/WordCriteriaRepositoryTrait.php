<?php

namespace App\Repository;

use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;
use App\Exception\NoWordFoundException;
use App\Specification\CriterionConverter;
use App\Specification\EntitiesAliases;
use App\Specification\Sort;
use App\Specification\WordCriteria;
use Doctrine\ORM\QueryBuilder;
use Random\RandomException;
use Symfony\Contracts\Service\Attribute\Required;

/**
 * Trait allowing WordCriteria to be applied on a QueryBuilder.
 *
 * @author Wilhelm Zwertvaegher
 */
trait WordCriteriaRepositoryTrait
{
    private CriterionConverter $criterionConverter;

    #[Required]
    public function setCriterionConverter(CriterionConverter $criterionConverter): void
    {
        $this->criterionConverter = $criterionConverter;
    }

    /**
     * @throws NoWordFoundException
     * @throws RandomException
     */
    protected function applyWordCriteria(QueryBuilder $qb, WordCriteria $criteria, Sort $sort = Sort::RANDOM, ?EntitiesAliases $aliases = null): void
    {
        if (null === $aliases) {
            $aliases = new EntitiesAliases(Word::class, 'word', Subject::class, 's', Qualifier::class, 'q');
        }

        $select = $qb->getDQLPart('select');
        $qb->andWhere($aliases->getAlias(Word::class).'.lang = :lang')
            ->setParameter('lang', $criteria->getLang());

        $this->criterionConverter->applyAll($qb, $criteria->getCriteria(), $aliases);

        if (Sort::RANDOM === $sort) {
            $count = (int) $qb
                ->select('COUNT('.$aliases->getAlias(Word::class).'.id)')
                ->getQuery()
                ->getSingleScalarResult();

            // reset to the original selects
            // FIXME : this seems a bit of a dirty hack and may be hazardous ?
            $qb->resetDQLPart('select');
            foreach ($select as $s) {
                $qb->add('select', $s);
            }

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
