<?php

namespace App\Repository;

use App\Entity\Contact;
use App\Entity\Suggestion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Suggestion>
 *
 * @author Wilhelm Zwertvaegher
 */
class SuggestionRepository extends ServiceEntityRepository implements SuggestionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Suggestion::class);
    }

    public function getById(int $id): ?Suggestion
    {
        return parent::find($id);
    }
}
