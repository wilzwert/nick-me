<?php

namespace App\Repository;

use App\Entity\Subject;
use App\Specification\Sort;
use App\Specification\WordCriteria;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Wilhelm Zwertvaegher
 * @extends ServiceEntityRepository<Subject>
 */
class SubjectRepository extends ServiceEntityRepository implements SubjectRepositoryInterface
{
    use WordCriteriaRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry,Subject::class);
    }

    public function findByWordId(int $wordId): ?Subject
    {
        return parent::findOneBy(['word' => $wordId]);
    }

    public function findOne(WordCriteria $criteria, Sort $sort = Sort::RANDOM): ?Subject
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.word', 'word');

        $this->applyWordCriteria($qb, $criteria, $sort);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
