<?php

namespace App\UseCase;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Result\GeneratedNickData;

/**
 * @author Wilhelm Zwertvaegher
 */
interface GenerateNickInterface
{
    public function __invoke(GenerateNickCommand $generateNickCommand): GeneratedNickData;

}
