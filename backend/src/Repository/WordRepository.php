<?php

namespace App\Repository;

use App\Entity\Word;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @author Wilhelm Zwertvaegher
 * @extends ServiceEntityRepository<Word>
 */
class WordRepository extends ServiceEntityRepository implements WordRepositoryInterface
{

    public function findBySlug($slug): ?Word
    {
        // TODO: Implement findBySlug() method.
        return null;
    }

    public function save(Word $word): void
    {
        // TODO: Implement save() method.
    }
}
