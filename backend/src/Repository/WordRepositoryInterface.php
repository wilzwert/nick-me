<?php

namespace App\Repository;

use App\Entity\Word;

/**
 * @author Wilhelm Zwertvaegher
 */
interface WordRepositoryInterface
{
    public function findBySlug($slug): ?Word;

    public function findById(int $id): ?Word;

}
