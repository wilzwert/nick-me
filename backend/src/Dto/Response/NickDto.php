<?php

namespace App\Dto\Response;

use App\Enum\OffenseLevel;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class NickDto
{
    /**
     * @param OffenseLevel $offenseLevel
     * @param array<NickWordDto> $words
     */
    public function __construct(
        public OffenseLevel $offenseLevel,
        public array $words = []
    ) {
    }
}
