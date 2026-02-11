<?php

namespace App\Application\UseCase;

use App\Dto\Command\CreateContactCommand;
use App\Entity\Contact;

/**
 * @author Wilhelm Zwertvaegher
 */
interface CreateContactInterface
{
    public function __invoke(CreateContactCommand $command): Contact;
}
