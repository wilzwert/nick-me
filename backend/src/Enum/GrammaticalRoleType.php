<?php

namespace App\Enum;

use App\Entity\Qualifier;
use App\Entity\Subject;

/**
 * @author Wilhelm Zwertvaegher
 */
enum GrammaticalRoleType: string
{
    case SUBJECT = 'subject';
    case QUALIFIER = 'qualifier';

    /**
     * @param class-string $className
     * @return GrammaticalRoleType
     */
    public static function fromClass(string $className): GrammaticalRoleType
    {
        return match ($className) {
            Subject::class => self::SUBJECT,
            Qualifier::class => self::QUALIFIER,
            default => throw new \InvalidArgumentException('Unknown class: ' . $className)
        };
    }
}
