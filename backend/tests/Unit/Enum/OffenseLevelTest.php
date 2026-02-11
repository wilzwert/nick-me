<?php

namespace App\Tests\Unit\Enum;

use App\Enum\OffenseLevel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class OffenseLevelTest extends TestCase
{
    /**
     * @return list<array{int, string}>
     */
    public static function fromStringTests(): array
    {
        return [
            [1, 'loW'],
            [5, 'MedIUM'],
            [10, 'HigH'],
            [15, 'very_high'],
            [20, 'Max'],
        ];
    }

    #[DataProvider('fromStringTests')]
    #[Test]
    public function shouldFindForString(int $expectedValue, string $stringValue): void
    {
        self::assertEquals($expectedValue, OffenseLevel::fromString($stringValue)->value);
    }

    #[Test]
    public function whenUnknownOffenseLevelThenShouldThrowValueError(): void
    {
        $this->expectException(\ValueError::class);
        OffenseLevel::fromString('unknown');
    }
}
