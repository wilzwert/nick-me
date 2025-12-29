<?php

namespace App\Specification;

/**
 * @author Wilhelm Zwertvaegher
 */
enum ValueCriterionCheck: string
{
    case EQ = '=';
    case NEQ = '!=';
    case GT = '>';
    case GTE = '>=';
    case LT = '<';
    case LTE = '<=';
}
