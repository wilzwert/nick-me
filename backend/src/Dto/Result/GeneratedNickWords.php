<?php

namespace App\Dto\Result;

use App\Enum\OffenseLevel;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class GeneratedNickWords
{
    /**
     * @param list<GeneratedNickWord> $words
     */
    public function __construct(
        private WordGender $targetGender,
        private OffenseLevel $targetOffenseLevel,
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

    /**
     * @return list<GeneratedNickWord>
     */
    public function getWords(): array
    {
        return $this->words;
    }

    public function getFinalLabel(): string
    {
        $finalLabel = '';
        foreach ($this->words as $resultWord) {
            $finalLabel .= $resultWord->label.$resultWord->separatorAfter;
        }

        return trim($finalLabel);
    }
}
