<?php

namespace App\Dto\Csv;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class CsvSubject
{
    public function __construct(
        public string $label,
        public string $gender,
        public ?string $offenseLevel = null
    ) {
    }

}
