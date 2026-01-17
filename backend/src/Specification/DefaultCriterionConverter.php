<?php

namespace App\Specification;

use App\Specification\Criterion\Criterion;
use App\Specification\Criterion\EnumCriterion;
use App\Specification\Criterion\ValueCriterion;
use App\Specification\Criterion\ValuesCriterion;

/**
 * @author Wilhelm Zwertvaegher
 */
class DefaultCriterionConverter implements CriterionConverter
{
    /**
     * @var array<class-string<Criterion>, \Closure>
     */
    private array $builders;

    public function __construct()
    {
        $this->builders = [
            EnumCriterion::class => function (QueryBuilderInterface $qb, EnumCriterion $criterion, int $criterionIndex, EntitiesAliases $aliases) {
                $qb->andWhere($aliases->getAlias($criterion->getTargetEntity()).'.'.$criterion->getField()." IN (:values{$criterionIndex})")
                    ->setParameter("values{$criterionIndex}", $criterion->getAllowedValues());
            },
            ValueCriterion::class => function (QueryBuilderInterface $qb, ValueCriterion $criterion, int $criterionIndex, EntitiesAliases $aliases) {
                $qb->andWhere($aliases->getAlias($criterion->getTargetEntity()).'.'.$criterion->getField().'  '.$criterion->getCheck()->value." :value{$criterionIndex}")
                    ->setParameter("value{$criterionIndex}", $criterion->getValue());
            },
            ValuesCriterion::class => function (QueryBuilderInterface $qb, ValuesCriterion $criterion, int $criterionIndex, EntitiesAliases $aliases) {
                $qb->andWhere($aliases->getAlias($criterion->getTargetEntity()).'.'.$criterion->getField().'  '.$criterion->getCheck()->value." (:values{$criterionIndex})")
                    ->setParameter("values{$criterionIndex}", $criterion->getValues());
            },
        ];
    }

    /**
     * @param array<Criterion> $criteria
     */
    public function applyAll(QueryBuilderInterface $qb, array $criteria, EntitiesAliases $aliases): void
    {
        foreach ($criteria as $i => $criterion) {
            $this->apply($qb, $criterion, $i, $aliases);
        }
    }

    public function apply(QueryBuilderInterface $qb, Criterion $criterion, int $criterionIndex, EntitiesAliases $aliases): void
    {
        if ($criterion->shouldApply()) {
            foreach ($this->builders as $class => $builder) {
                if ($criterion instanceof $class) {
                    $builder($qb, $criterion, $criterionIndex, $aliases);
                }
            }
        }
    }
}
