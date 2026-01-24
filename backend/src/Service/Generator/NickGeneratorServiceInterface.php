<?php

namespace App\Service\Generator;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Result\GeneratedNickData;
use App\Exception\NickNotFoundException;
use App\Exception\NoWordFoundException;
use Random\RandomException;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NickGeneratorServiceInterface
{
    /**
     * @param GenerateNickCommand $command
     * @return GeneratedNickData
     * @throws NoWordFoundException
     * @throws NickNotFoundException
     */
    public function generateNick(GenerateNickCommand $command): GeneratedNickData;
}
