<?php

namespace App\Dto\Result;

use App\Entity\Nick;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class GeneratedNickData
{
    /**
     * @param list<GeneratedNickWord> $words
     */
    public function __construct(
        private WordGender $targetGender,
        private OffenseLevel $targetOffenseLevel,
        private Nick $nick,
        private array $words,
    ) {
    }

    public function getTargetGender(): WordGender
    {
        return $this->targetGender;
    }

    public function getTargetOffenseLevel(): OffenseLevel
    {
        return $this->targetOffenseLevel;
    }

    public function getNick(): Nick
    {
        return $this->nick;
    }

    /**
     * @return list<GeneratedNickWord>
     */
    public function getWords(): array
    {
        return $this->words;
    }
}
