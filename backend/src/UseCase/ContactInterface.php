<?php

namespace App\UseCase;

use App\Dto\Command\ContactCommand;

/**
 * @author Wilhelm Zwertvaegher
 */
interface ContactInterface
{
    public function __invoke(ContactCommand $command): void;

}
