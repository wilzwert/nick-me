<?php

namespace App\Dto\Result;

use App\Enum\GrammaticalRoleType;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class GeneratedNickWord
{
    public function __construct(
        public int $id,
        public string $label,
        public GrammaticalRoleType $type,
        public string $separatorAfter = ' ',
    ) {
    }
}
