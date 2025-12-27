<?php

namespace App\Service\Data;

use App\Entity\Subject;
use App\Entity\Word;
use App\Repository\SubjectRepositoryInterface;
use App\Specification\Sort;
use App\Specification\WordCriteria;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class SubjectService implements SubjectServiceInterface
{

    public function __construct(
        private readonly SubjectRepositoryInterface $repository,
        private readonly EntityManagerInterface $entityManager)
    {
    }

    public function createOrUpdate(Word $word): Subject
    {

        $subject = $this->repository->findByWordId($word->getId());
        if (!$subject) {
            $subject = new Subject($word);
        }
        $this->save($subject);

        return $subject;
    }

    public function deleteIfExists(int $wordId): void
    {
        $qualifier = $this->repository->findByWordId($wordId);
        if ($qualifier) {
            $this->entityManager->remove($qualifier);
        }
    }

    public function save(Subject $subject): void
    {
        $this->entityManager->persist($subject);
    }

    public function findOneRandomly(WordCriteria $criteria): Subject
    {
        return $this->repository->findOne($criteria, Sort::RANDOM);

    }
}
