<?php

namespace App\Service\Data;

use App\Entity\GrammaticalRole;
use App\Entity\Subject;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Repository\SubjectRepositoryInterface;
use App\Specification\Criterion\ValueCriterion;
use App\Specification\Criterion\ValueCriterionCheck;
use App\Specification\Sort;
use App\Specification\WordCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class SubjectService implements SubjectServiceInterface
{
    public function __construct(
        private readonly SubjectRepositoryInterface $repository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ClockInterface $clock,
    ) {
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
        $subject = $this->repository->findByWordId($wordId);
        if ($subject) {
            $this->entityManager->remove($subject);
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

    public function getGrammaticalRole(): GrammaticalRoleType
    {
        return GrammaticalRoleType::SUBJECT;
    }

    /**
     * @return ?Subject
     */
    public function findByWordId(int $wordId): ?GrammaticalRole
    {
        return $this->repository->findByWordId($wordId);
    }

    public function findSimilar(GrammaticalRole $other, WordCriteria $criteria): ?GrammaticalRole
    {
        if (!$other instanceof Subject) {
            throw new \LogicException('Cannot find another subject because $other param is not an instance of Subject');
        }

        $criteria->addCriterion(new ValueCriterion(Word::class, 'id', $other->getWord()->getId(), ValueCriterionCheck::NEQ));

        return $this->repository->findOne($criteria);
    }

    /**
     * @param Subject $grammaticalRole
     */
    public function incrementUsageCount(GrammaticalRole $grammaticalRole): void
    {
        $grammaticalRole->incrementUsageCount($this->clock->now());
        $this->save($grammaticalRole);
    }
}
