<?php

namespace App\Service\Data;

use App\Entity\Nick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Repository\NickRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class NickService implements NickServiceInterface
{
    public function __construct(
        private NickRepositoryInterface $repository,
        private EntityManagerInterface  $entityManager,
        private ClockInterface          $clock,
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
        if (!($nick = $this->repository->getByProperties($subject, $qualifier, $targetGender))) {
            $nick = new Nick(
                $label,
                $subject,
                $qualifier,
                $targetGender,
                $offenseLevel,
                $now = $this->clock->now(),
                $now
            );
            $this->save($nick);
        }
        return $nick;
    }
}
