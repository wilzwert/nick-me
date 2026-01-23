<?php

namespace App\Service\Data;

use App\Dto\Command\ContactCommand;
use App\Entity\Contact;
use App\Entity\Notification;
use App\Service\Notification\NotificationProps;

/**
 * @author Wilhelm Zwertvaegher
 */
interface ContactServiceInterface
{
    public function save(Contact $contact): void;

    public function create(ContactCommand $command): Contact;
}
