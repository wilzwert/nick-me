<?php

namespace App\UseCase;

use App\Dto\Request\RandomWordRequest;
use App\Dto\Response\NickDto;

/**
 * @author Wilhelm Zwertvaegher
 */
interface GenerateNickInterface
{
    public function __invoke(RandomWordRequest $command): NickDto;

}
