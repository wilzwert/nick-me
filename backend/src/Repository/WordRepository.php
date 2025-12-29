<?php

namespace App\Repository;

use App\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @author Wilhelm Zwertvaegher
 * @extends ServiceEntityRepository<Word>
 */
class WordRepository extends ServiceEntityRepository implements WordRepositoryInterface
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry,Word::class);
    }

    public function findBySlug(string $slug): ?Word
    {
        return parent::findOneBy(['slug' => $slug]);
    }

    public function findById(int $id): ?Word
    {
        return parent::find($id);
    }
}
