<?php

namespace App\Dto\Csv;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class CsvQualifier
{
    public function __construct(
        public string $label,
        public ?string $gender,
        public string $position,
        public ?string $offenseLevel = null,
    ) {
    }
}
