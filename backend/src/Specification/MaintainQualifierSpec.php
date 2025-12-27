<?php

namespace App\Specification;

use App\Enum\QualifierPosition;

/**
 * @author Wilhelm Zwertvaegher
 */
readonly class MaintainQualifierSpec
{
    public function __construct(
        private QualifierPosition $position
    )
    {
    }

    public function getPosition(): QualifierPosition
    {
        return $this->position;
    }

}
