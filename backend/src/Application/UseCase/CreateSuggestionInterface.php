<?php

namespace App\Application\UseCase;

use App\Dto\Command\CreateSuggestionCommand;
use App\Entity\Suggestion;

/**
 * @author Wilhelm Zwertvaegher
 */
interface CreateSuggestionInterface
{
    public function __invoke(CreateSuggestionCommand $command): Suggestion;

}
