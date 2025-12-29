<?php

namespace App\UseCase;

use App\Dto\Request\RandomNickRequest;
use App\Dto\Request\RandomWordRequest;
use App\Dto\Response\NickDto;
use App\Dto\Response\NickWordDto;

/**
 * @author Wilhelm Zwertvaegher
 */
interface GetWordInterface
{
    public function __invoke(RandomWordRequest $request): NickWordDto;

}
