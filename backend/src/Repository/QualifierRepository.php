<?php

namespace App\Repository;

use App\Entity\Qualifier;
use App\Specification\DoctrineQueryBuilder;
use App\Specification\Sort;
use App\Specification\WordCriteria;
use App\Specification\WordCriteriaService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Wilhelm Zwertvaegher
 *
 * @extends ServiceEntityRepository<Qualifier>
 */
class QualifierRepository extends ServiceEntityRepository implements QualifierRepositoryInterface
{
    public function __construct(ManagerRegistry $registry, private readonly WordCriteriaService $wordCriteriaService)
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

        $this->wordCriteriaService->applyWordCriteria(new DoctrineQueryBuilder($qb), $criteria, $sort);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
