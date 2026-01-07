<?php

namespace App\Repository;

use App\Entity\Nick;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\WordGender;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nick>
 *
 * @author Wilhelm Zwertvaegher
 */
class NickRepository extends ServiceEntityRepository implements NickRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nick::class);
    }

    public function getById(int $id): ?Nick
    {
        return parent::find($id);
    }

    public function getByProperties(Subject $subject, Qualifier $qualifier, WordGender $targetGender): ?Nick
    {
        return parent::findOneBy(['subject' => $subject, 'qualifier' => $qualifier, 'targetGender' => $targetGender]);
    }
}
