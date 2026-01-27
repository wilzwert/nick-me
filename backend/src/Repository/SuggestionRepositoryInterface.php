<?php

namespace App\Repository;

use App\Entity\Suggestion;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SuggestionRepositoryInterface
{
    public function getById(int $id): ?Suggestion;
}
