<?php

namespace App\UseCase;

use App\Dto\Command\CreateContactCommand;
use App\Entity\Contact;
use App\Message\CommandBus;
use App\Message\SendNotificationCommand;
use App\Service\Data\ContactServiceInterface;
use App\Service\Data\NotificationServiceInterface;
use App\Service\Notification\NotificationPropsFactoryInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class CreateContact implements CreateContactInterface
{
    public function __construct(
        private ContactServiceInterface $contactService,
        private NotificationServiceInterface $notificationService,
        private EntityManagerInterface $entityManager,
        private NotificationPropsFactoryInterface $notificationFactory,
        private CommandBus $commandBus,
    ) {
    }

    public function __invoke(CreateContactCommand $command): Contact
    {
        $contact = $this->contactService->create($command);
        $notificationProps = $this->notificationFactory->create($contact);
        $notification = $this->notificationService->create($notificationProps);
        $this->entityManager->flush();
        $this->commandBus->dispatch(new SendNotificationCommand($notification->getId()));
        return $contact;
    }
}
