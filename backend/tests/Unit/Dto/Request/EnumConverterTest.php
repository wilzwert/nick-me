<?php

namespace App\Tests\Unit\Dto\Request;

use App\Dto\Request\EnumConverter;
use App\Enum\Enum;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class EnumConverterTest extends TestCase
{
    private EnumConverter $underTest;

    protected function setUp(): void
    {
        $this->underTest = new EnumConverter();
    }

    /**
     * @return list<array{class-string<Enum>, string, Enum}>
     */
    public static function successDataProvider(): array
    {
        return [
            [GrammaticalRoleType::class, 'subject', GrammaticalRoleType::SUBJECT],
            [GrammaticalRoleType::class, 'qualifier', GrammaticalRoleType::QUALIFIER],
            [Lang::class, 'en', Lang::EN],
            [Lang::class, 'FR', Lang::FR],
            [OffenseLevel::class, 'max', OffenseLevel::MAX],
            [OffenseLevel::class, '15', OffenseLevel::VERY_HIGH],
            [OffenseLevel::class, '14', OffenseLevel::HIGH],
            [WordGender::class, 'f', WordGender::F],
            [WordGender::class, 'm', WordGender::M],
            [WordGender::class, 'neutral', WordGender::NEUTRAL],
            [WordStatus::class, 'pending', WordStatus::PENDING],
        ];
    }

    /**
     * @param class-string<Enum> $className
     */
    #[Test]
    #[DataProvider('successDataProvider')]
    public function shouldConvert(string $className, string $value, Enum $expectedEnumCase): void
    {
        self::assertEquals($expectedEnumCase, $this->underTest->convert($className, $value));
    }

    /**
     * @return list<array{class-string<Enum>, string}>
     */
    public static function errorDataProvider(): array
    {
        return [
            [GrammaticalRoleType::class, 'unknown'],
            [OffenseLevel::class, 'unknown'],
            [WordGender::class, 'unknown'],
            [WordStatus::class, 'unknown'],
        ];
    }

    /**
     * @param class-string<Enum> $className
     */
    #[Test]
    #[DataProvider('errorDataProvider')]
    public function shouldThrowValueError(string $className, string $value): void
    {
        self::expectException(\ValueError::class);
        $this->underTest->convert($className, $value);
    }
}
