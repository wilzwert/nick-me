<?php

namespace App\Service\Generator;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Result\NickGenerationResult;
use App\Entity\Nick;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NickServiceInterface
{
    public function generateNick(GenerateNickCommand $command) :NickGenerationResult;

    public function save(Nick $nick): void;
}
