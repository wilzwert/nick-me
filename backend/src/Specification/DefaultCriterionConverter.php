<?php

namespace App\Specification;

use App\Specification\Criterion\Criterion;
use App\Specification\Criterion\EnumCriterion;
use App\Specification\Criterion\ValueCriterion;
use App\Specification\Criterion\ValuesCriterion;
use Closure;
use Doctrine\ORM\QueryBuilder;

/**
 * @author Wilhelm Zwertvaegher
 */
class DefaultCriterionConverter implements CriterionConverter
{

    /**
     * @var array<class-string<Criterion>, Closure>
     */
    private array $builders;

    public function __construct()
    {
        $this->builders = [
            EnumCriterion::class => function (QueryBuilder $qb, EnumCriterion $criterion, int $criterionIndex, EntitiesAliases $aliases) {
                $qb->andWhere($aliases->getAlias($criterion->getTargetEntity())."." . $criterion->getField() . " IN (:values{$criterionIndex})")
                    ->setParameter("values{$criterionIndex}", $criterion->getAllowedValues());
            },
            ValueCriterion::class => function (QueryBuilder $qb, ValueCriterion $criterion, int $criterionIndex, EntitiesAliases $aliases) {
                $qb->andWhere($aliases->getAlias($criterion->getTargetEntity())."." . $criterion->getField() . "  ".$criterion->getCheck()->value." :value{$criterionIndex}")
                    ->setParameter("value{$criterionIndex}", $criterion->getValue());
            },
            ValuesCriterion::class => function (QueryBuilder $qb, ValuesCriterion $criterion, int $criterionIndex, EntitiesAliases $aliases) {
                $qb->andWhere($aliases->getAlias($criterion->getTargetEntity())."." . $criterion->getField() . "  ".$criterion->getCheck()->value." (:values{$criterionIndex})")
                    ->setParameter("values{$criterionIndex}", $criterion->getValues());
            }

        ];
    }

    /**
     * @param QueryBuilder $qb
     * @param array<Criterion> $criteria
     * @param EntitiesAliases $aliases
     * @return void
     */
    public function applyAll(QueryBuilder $qb, array $criteria, EntitiesAliases $aliases): void
    {
        foreach ($criteria as $i => $criterion) {
            $this->apply($qb, $criterion, $i, $aliases);
        }
    }

    /**
     * @param QueryBuilder $qb
     * @param Criterion $criterion
     * @param int $criterionIndex
     * @param EntitiesAliases $aliases
     * @return void
     */
    public function apply(QueryBuilder $qb, Criterion $criterion, int $criterionIndex, EntitiesAliases $aliases): void
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
