<?php

namespace App\Specification\Criterion;

/**
 * @author Wilhelm Zwertvaegher
 */
enum ValuesCriterionCheck: string
{
    case IN = 'IN';
    case NOT_IN = 'NOT IN';
}
