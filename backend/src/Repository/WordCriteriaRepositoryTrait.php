<?php

namespace App\Repository;

use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;
use App\Exception\NoWordFoundException;
use App\Specification\CriterionConverter;
use App\Specification\EntitiesAliases;
use App\Specification\EnumCriterion;
use App\Specification\Sort;
use App\Specification\WordCriteria;
use Doctrine\ORM\QueryBuilder;
use Symfony\Contracts\Service\Attribute\Required;

trait WordCriteriaRepositoryTrait
{
    private CriterionConverter $criterionConverter;

    #[Required]
    public function setCriterionConverter(CriterionConverter $criterionConverter): void
    {
        $this->criterionConverter = $criterionConverter;
    }

    protected function applyWordCriteria(QueryBuilder $qb, WordCriteria $criteria, Sort $sort = Sort::RANDOM, ?EntitiesAliases $aliases = null): void
    {
        if (null === $aliases) {
            $aliases = new EntitiesAliases(Word::class, 'word', Subject::class, 's', Qualifier::class, 'q');
        }

        $select = $qb->getDQLPart('select');
        $qb ->andWhere($aliases->getAlias(Word::class).".lang = :lang")
            ->setParameter('lang', $criteria->getLang());

        $this->criterionConverter->applyAll($qb, $criteria->getCriteria(), $aliases);

        if (count($criteria->getExclusions())) {
            $qb->andWhere($aliases->getAlias(Word::class).".id NOT IN (:exclusions)")
                ->setParameter('exclusions', $criteria->getExclusions());
        }

        if ($sort === Sort::RANDOM) {
            $count = (int) $qb
                ->select("COUNT(".$aliases->getAlias(Word::class).".id)")
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
            }
            else throw new NoWordFoundException("No word found.");
        }

        $qb->setMaxResults(1);
    }

}
