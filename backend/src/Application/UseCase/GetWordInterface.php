<?php

namespace App\Application\UseCase;

use App\Dto\Command\GetWordCommand;
use App\Dto\Response\NickWordDto;

/**
 * @author Wilhelm Zwertvaegher
 */
interface GetWordInterface
{
    public function __invoke(GetWordCommand $command): NickWordDto;
}
