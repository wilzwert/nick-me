<?php

namespace App\Service\Data;

use App\Dto\Command\ContactCommand;
use App\Entity\Contact;
use App\Entity\Notification;
use App\Enum\NotificationStatus;
use App\Service\Notification\NotificationProps;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Clock\ClockInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class ContactService implements ContactServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClockInterface $clock,
    ) {
    }

    public function save(Contact $contact): void
    {
        $this->entityManager->persist($contact);
    }

    public function create(ContactCommand $command): Contact
    {
        $contact = new Contact(
            senderEmail: $command->getSenderEmail(),
            content: $command->getContent(),
            createdAt: $this->clock->now()
        );

        $this->save($contact);

        return $contact;
    }
}
