<?php

namespace App\Service\Data;

use App\Entity\Nick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Repository\NickRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
class NickService implements NickServiceInterface
{
    public function __construct(
        private readonly NickRepositoryInterface $repository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Nick $nick): void
    {
        $this->entityManager->persist($nick);
    }

    public function getNick(int $id): ?Nick
    {
        return $this->repository->getById($id);
    }

    public function incrementUsageCount(Nick $nick): void
    {
        $nick->incrementUsageCount();
        $this->save($nick);
    }

    public function getOrCreate(Subject $subject, Qualifier $qualifier, WordGender $targetGender, OffenseLevel $offenseLevel, string $label): Nick
    {
        return $this->repository->getByProperties($subject, $qualifier, $targetGender) ?? new Nick(
            $label,
            $subject,
            $qualifier,
            $targetGender,
            $offenseLevel
        );
    }
}
