<?php

namespace App\Dto\Response;

use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Enum\WordStatus;

/**
 * @author Wilhelm Zwertvaegher
 */
final readonly class FullWordDto
{
    /**
     * @param array<value-of<GrammaticalRoleType>, QualifierDto|SubjectDto> $types
     */
    public function __construct(
        public int $id,
        public string $label,
        public string $slug,
        public WordGender $gender,
        public Lang $lang,
        public OffenseLevel $offenseLevel,
        public WordStatus $status,
        public array $types,
    ) {
    }
}
