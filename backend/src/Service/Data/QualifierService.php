<?php

namespace App\Service\Data;

use App\Entity\GrammaticalRole;
use App\Entity\Qualifier;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Repository\QualifierRepositoryInterface;
use App\Specification\MaintainQualifierSpec;
use App\Specification\ValueCriterion;
use App\Specification\ValueCriterionCheck;
use App\Specification\WordCriteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

/**
 * @author Wilhelm Zwertvaegher
 */
class QualifierService implements QualifierServiceInterface
{
    public function __construct(
        private readonly QualifierRepositoryInterface $repository,
        private readonly EntityManagerInterface $entityManager)
    {
    }

    public function createOrUpdate(Word $word, MaintainQualifierSpec $command): Qualifier
    {
        $qualifier = $this->repository->findByWordId($word->getId());
        if ($qualifier) {
            $qualifier->setPosition($command->getPosition());
        }
        else {
            $qualifier = new Qualifier($word, $command->getPosition());
        }
        $this->save($qualifier);

        return $qualifier;
    }

    public function deleteIfExists(int $wordId): void
    {
        $qualifier = $this->repository->findByWordId($wordId);
        if ($qualifier) {
            $this->entityManager->remove($qualifier);
        }
    }

    public function save(Qualifier $qualifier): void
    {
        $this->entityManager->persist($qualifier);
    }

    public function findOneRandomly(WordCriteria $criteria): Qualifier
    {
        $result = $this->repository->findOne($criteria);
        if(empty($result)) {
            throw new \LogicException('Could not find a Qualifier');
        }
        return $result;
    }

    public function getGrammaticalRole(): GrammaticalRoleType
    {
        return GrammaticalRoleType::QUALIFIER;
    }

    /**
     * @param int $wordId
     * @return ?Qualifier
     */
    public function findByWordId(int $wordId): ?GrammaticalRole
    {
        return $this->repository->findByWordId($wordId);
    }

    public function findAnother(GrammaticalRole $other, WordCriteria $criteria): ?GrammaticalRole
    {
        if (! $other instanceof Qualifier) {
            throw new \LogicException('Cannot find another qualifier because $other param is not an instance of Qualifier');
        }

        $criteria->addCriterion(new ValueCriterion(Qualifier::class, 'id', $other->getWord()->getId(), ValueCriterionCheck::NEQ));
        // add qualifier position to criteria
        $criteria->addCriterion(new ValueCriterion(Qualifier::class, 'position', $other->getPosition(), ValueCriterionCheck::EQ));

        return $this->repository->findOne($criteria);
    }
}

