<?php

namespace App\Service\Data;

use App\Dto\Command\CreateSuggestionCommand;
use App\Entity\Suggestion;

/**
 * @author Wilhelm Zwertvaegher
 */
interface SuggestionServiceInterface
{
    public function save(Suggestion $suggestion): void;

    public function create(CreateSuggestionCommand $command): Suggestion;
}
