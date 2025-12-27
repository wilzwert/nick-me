<?php

namespace App\Dto\Response;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use App\Enum\WordType;

/**
 * @author Wilhelm Zwertvaegher
 */
final readonly class FullWordDto
{
    /**
     * @param int $id
     * @param string $label
     * @param WordGender $gender
     * @param Lang $lang
     * @param OffenseLevel $offenseLevel
     * @param WordStatus $status
     * @param array<value-of<WordType>, QualifierDto|SubjectDto> $types
     */
    public function __construct(
        public int        $id,
        public string     $label,
        public WordGender $gender,
        public Lang       $lang,
        public OffenseLevel $offenseLevel,
        public WordStatus $status,
        public array      $types
    ) {
    }

}
