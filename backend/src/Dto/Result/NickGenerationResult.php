<?php

namespace App\Dto\Result;

use App\Entity\Nick;
use App\Enum\Lang;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class NickGenerationResult
{
    public function __construct(
        private WordGender $targetGender,
        private Nick $nick
    ) {
    }

    public function getTargetGender(): WordGender
    {
        return $this->targetGender;
    }

    public function getNick(): Nick
    {
        return $this->nick;
    }
}
