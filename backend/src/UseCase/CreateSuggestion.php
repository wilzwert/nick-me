<?php

namespace App\UseCase;

use App\Dto\Command\CreateSuggestionCommand;
use App\Entity\Suggestion;
use App\Exception\WordAlreadyExistsException;
use App\Message\CommandBus;
use App\Message\SendNotificationCommand;
use App\Service\Data\NotificationServiceInterface;
use App\Service\Data\SuggestionServiceInterface;
use App\Service\Data\WordServiceInterface;
use App\Service\Data\WordSluggerInterface;
use App\Service\Notification\NotificationPropsFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class CreateSuggestion implements CreateSuggestionInterface
{
    public function __construct(
        private WordServiceInterface $wordService,
        private SuggestionServiceInterface $suggestionService,
        private NotificationServiceInterface $notificationService,
        private EntityManagerInterface $entityManager,
        private NotificationPropsFactoryInterface $notificationFactory,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(CreateSuggestionCommand $command): Suggestion
    {
        // first, let's try and find an existing word
        $existingWord = $this->wordService->getByLabel($command->getLabel());
        if (null !== $existingWord) {
            throw new WordAlreadyExistsException(sprintf('Word %s already exists', $command->getLabel()));
        }
        $suggestion = $this->suggestionService->create($command);
        $notificationProps = $this->notificationFactory->create($suggestion);
        $notification = $this->notificationService->create($notificationProps);
        $this->entityManager->flush();
        $this->commandBus->dispatch(new SendNotificationCommand($notification->getId()));

        return $suggestion;
    }
}
