<?php

namespace App\Dto\Response;

use App\Enum\GrammaticalRoleType;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class NickWordDto
{
    public function __construct(
        public int $id,
        public string $label,
        public GrammaticalRoleType $role,
    ) {
    }
}
