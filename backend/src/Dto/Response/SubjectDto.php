<?php

namespace App\Dto\Response;

/**
 * @author Wilhelm Zwertvaegher
 */
final readonly class SubjectDto
{
    public function __construct(
        public int $usagesCount
    ) {
    }
}
