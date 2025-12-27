<?php

namespace App\Service\Data;

use App\Entity\Qualifier;
use App\Entity\Word;
use App\Repository\QualifierRepositoryInterface;
use App\Specification\MaintainQualifierSpec;
use App\Specification\WordCriteria;
use Doctrine\ORM\EntityManagerInterface;

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
}

