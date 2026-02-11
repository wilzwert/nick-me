<?php

namespace App\Service\Generator;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Result\GeneratedNickData;
use App\Exception\NickNotFoundException;
use App\Exception\NoQualifierFoundException;
use App\Exception\NoSubjectFoundException;

/**
 * @author Wilhelm Zwertvaegher
 */
interface NickGeneratorServiceInterface
{
    /**
     * @throws NickNotFoundException
     * @throws NoQualifierFoundException
     * @throws NoSubjectFoundException
     */
    public function generateNick(GenerateNickCommand $command): GeneratedNickData;
}
