<?php

namespace App\Tests\Unit\Enum;

use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Enum\GrammaticalRoleType;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class WordTypeTest extends TestCase
{

    public static function fromClassTests(): array
    {
        return [
            ['subject', Subject::class],
            ['qualifier', Qualifier::class],
        ];
    }

    #[DataProvider('fromClassTests')]
    #[Test]
    public function shouldFindForClass(string $expectedValue, string $className): void
    {
        self::assertEquals($expectedValue, GrammaticalRoleType::fromClass($className)->value);
    }

    #[Test]
    public function whenUnknownWordType_thenShouldThrowInvalidArgumentException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        GrammaticalRoleType::fromClass('unknown');
    }
}
