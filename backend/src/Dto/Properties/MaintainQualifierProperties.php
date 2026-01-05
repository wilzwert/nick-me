<?php

namespace App\Dto\Properties;

use App\Enum\QualifierPosition;

/**
 * DTO used to pass properties to a service to maintain a Qualifier
 * @author Wilhelm Zwertvaegher
 */
readonly class MaintainQualifierProperties
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
