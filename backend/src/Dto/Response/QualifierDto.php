<?php

namespace App\Dto\Response;

use App\Enum\QualifierPosition;

/**
 * @author Wilhelm Zwertvaegher
 */
final readonly class QualifierDto
{
    public function __construct(
       public int $usagesCount,
       public QualifierPosition $position
    ) {
    }
}
