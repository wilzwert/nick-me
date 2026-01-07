<?php

namespace App\Repository;

use App\Entity\Qualifier;
use App\Specification\Sort;
use App\Specification\WordCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Wilhelm Zwertvaegher
 *
 * @extends ServiceEntityRepository<Qualifier>
 */
class QualifierRepository extends ServiceEntityRepository implements QualifierRepositoryInterface
{
    use WordCriteriaRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Qualifier::class);
    }

    public function findByWordId(int $wordId): ?Qualifier
    {
        return parent::findOneBy(['word' => $wordId]);
    }

    public function findOne(WordCriteria $criteria, Sort $sort = Sort::RANDOM): ?Qualifier
    {
        $qb = $this->createQueryBuilder('q')
            ->join('q.word', 'word');

        $this->applyWordCriteria($qb, $criteria, $sort);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
