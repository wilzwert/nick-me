<?php

namespace App\Dto\Response;

use App\Enum\OffenseLevel;
use App\Enum\WordGender;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class NickDto
{
    /**
     * @param array<NickWordDto> $words
     */
    public function __construct(
        public int $id,
        public WordGender $gender,
        public OffenseLevel $offenseLevel,
        public array $words = [],
    ) {
    }
}
