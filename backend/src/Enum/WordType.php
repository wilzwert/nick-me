<?php

namespace App\Enum;

use App\Entity\Qualifier;
use App\Entity\Subject;

/**
 * @author Wilhelm Zwertvaegher
 */
enum WordType: string
{
    case SUBJECT = 'subject';
    case QUALIFIER = 'qualifier';

    /**
     * @param class-string $className
     * @return WordType
     */
    public static function fromClass(string $className): WordType
    {
        return match ($className) {
            Subject::class => self::SUBJECT,
            Qualifier::class => self::QUALIFIER,
            default => throw new \InvalidArgumentException('Unknown class: ' . $className)
        };
    }
}
