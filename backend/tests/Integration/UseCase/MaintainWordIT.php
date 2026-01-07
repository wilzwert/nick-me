<?php

namespace App\Tests\Integration\UseCase;

use App\Dto\Command\MaintainWordCommand;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use App\UseCase\MaintainWordInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class MaintainWordIT extends KernelTestCase
{
    private MaintainWordInterface $underTest;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->underTest = static::getContainer()->get(MaintainWordInterface::class);
    }

    public static function maintainWordDataProvider(): array
    {
        // $label, $expectedLabel, $lang, $gender, $offenseLevel, $status, $shouldBeQualifier, $qualifierPosition, $shouldBeSubject, $wordId
        return [
            ['NEW woRd', 'New Word', Lang::EN, WordGender::NEUTRAL, OffenseLevel::MAX, WordStatus::APPROVED, false, null, false, null],
            // shouldUpdateWordAnDeleteQualifier
            ['NEW woRd', 'New Word', Lang::EN, WordGender::NEUTRAL, OffenseLevel::MAX, WordStatus::APPROVED, false, null, true, 1],
            // shouldUpdateWordAnDeleteSubject
            ['NEW woRd', 'New Word', Lang::EN, WordGender::NEUTRAL, OffenseLevel::MAX, WordStatus::APPROVED, true, QualifierPosition::BEFORE, false, 1],
            // shouldCreateWordWithQualifierAndSubject
            ['NEW woRd', 'New Word', Lang::EN, WordGender::NEUTRAL, OffenseLevel::MAX, WordStatus::APPROVED, true, QualifierPosition::BEFORE, true, null],
        ];
    }

    #[Test]
    #[DataProvider('maintainWordDataProvider')]
    public function shouldMaintainWord(
        string $label,
        string $expectedLabel,
        Lang $lang,
        WordGender $gender,
        OffenseLevel $offenseLevel,
        WordStatus $status,
        bool $shouldBeQualifier,
        ?QualifierPosition $qualifierPosition,
        bool $shouldBeSubject,
        ?int $wordId): void
    {
        $command = new MaintainWordCommand(
            label: $label,
            gender: $gender,
            lang: $lang,
            offenseLevel: $offenseLevel,
            status: $status,
            asSubject: $shouldBeSubject,
            asQualifier: $shouldBeQualifier,
            qualifierPosition: $qualifierPosition,
            wordId: $wordId
        );

        $result = ($this->underTest)($command);

        self::assertNotNull($result->id);
        if (null !== $wordId) {
            self::assertEquals($wordId, $result->id);
        }

        $types = array_keys($result->types);
        if ($shouldBeQualifier) {
            self::assertContains(GrammaticalRoleType::QUALIFIER->value, $types);
            self::assertEquals($qualifierPosition, $result->types[GrammaticalRoleType::QUALIFIER->value]->position);
        } else {
            self::assertNotContains(GrammaticalRoleType::QUALIFIER->value, $types);
        }
        if ($shouldBeSubject) {
            self::assertContains(GrammaticalRoleType::SUBJECT->value, $types);
        } else {
            self::assertNotContains(GrammaticalRoleType::SUBJECT->value, $types);
        }
        self::assertEquals($lang, $result->lang);
        self::assertEquals($offenseLevel, $result->offenseLevel);
        self::assertEquals($gender, $result->gender);
        self::assertEquals($expectedLabel, $result->label);
    }
}
