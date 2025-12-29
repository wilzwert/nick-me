<?php

namespace App\UseCase;

use App\Dto\Request\RandomNickRequest;
use App\Dto\Response\NickDto;

/**
 * @author Wilhelm Zwertvaegher
 */
interface GenerateNickInterface
{
    public function __invoke(RandomNickRequest $request): NickDto;

}
