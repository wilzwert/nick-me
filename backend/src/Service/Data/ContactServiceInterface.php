<?php

namespace App\Service\Data;

use App\Dto\Command\CreateContactCommand;
use App\Entity\Contact;

/**
 * @author Wilhelm Zwertvaegher
 */
interface ContactServiceInterface
{
    public function save(Contact $contact): void;

    public function create(CreateContactCommand $command): Contact;
}
