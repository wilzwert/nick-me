<?php

namespace App\Service\Data;

use App\Dto\Command\CreateSuggestionCommand;
use App\Entity\Suggestion;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class SuggestionService implements SuggestionServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
    ) {
    }

    public function save(Suggestion $suggestion): void
    {
        $this->entityManager->persist($suggestion);
    }

    public function create(CreateSuggestionCommand $command): Suggestion
    {
        $suggestion = new Suggestion(
            creatorEmail: $command->getSenderEmail(),
            label: $command->getLabel(),
            createdAt: $this->clock->now()
        );

        $this->save($suggestion);

        return $suggestion;
    }
}
