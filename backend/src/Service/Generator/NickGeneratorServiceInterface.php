<?php

namespace App\Service\Generator;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Result\GeneratedNickData;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NickGeneratorServiceInterface
{
    public function generateNick(GenerateNickCommand $command) :GeneratedNickData;
}
