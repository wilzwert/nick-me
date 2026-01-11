<?php

namespace App\Tests\Integration\UseCase;

use App\Dto\Command\GetWordCommand;
use App\Dto\Request\RandomWordRequest;
use App\Enum\GrammaticalRoleType;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\UseCase\GetWordInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class GetWordIT extends KernelTestCase
{
    private GetWordInterface $underTest;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->underTest = static::getContainer()->get(GetWordInterface::class);
    }

    /**
     * Provides data for single random Subject commands
     * For each case, we provide : previous word id, target gender, offense level, expected label.
     *
     * @return array[]
     */
    public static function randomGenderedSubjectDataProvider(): array
    {
        return [
            [1, WordGender::F, OffenseLevel::LOW, 'Banane'],
            [1, WordGender::NEUTRAL, OffenseLevel::MAX, 'Hérétique'],
            [1, WordGender::M, OffenseLevel::MEDIUM, 'Camembert'],
        ];
    }

    #[Test]
    #[DataProvider('randomGenderedSubjectDataProvider')]
    public function shouldGetGenderedSubject(int $previousId, WordGender $targetGender, OffenseLevel $offenseLevel, $expectedLabel): void
    {
        $result = ($this->underTest)(new GetWordCommand(
            role: GrammaticalRoleType::SUBJECT,
            gender: $targetGender,
            // when requesting a new Subject, offenseLevel is exactly matched
            offenseLevel: $offenseLevel,
            previousId: $previousId
        ));

        self::assertEquals($expectedLabel, $result->label);
    }

    /**
     * Provides data for single random Qualifier requests
     * For each case, we provide : previous word id, target gender, offense level, expected label, exclusions if needed.
     *
     * @return array[]
     */
    public static function randomGenderedQualifierDataProvider(): array
    {
        return [
            [1, WordGender::NEUTRAL, OffenseLevel::MAX, [], 'Fataliste'],
            // for F and M genders, we force exclusion of the word with id 5, which is AUTO gendered
            [1, WordGender::F, OffenseLevel::MEDIUM, [5], 'Indiscrète'],
            [1, WordGender::M, OffenseLevel::MEDIUM, [5], 'Interrogateur'],
            // asking for an F or M gender while excluding actual F or M gendered qualifiers
            // allows to test getting a gender-formatted AUTO qualifier
            [1, WordGender::F, OffenseLevel::LOW, [6], 'Peureuse'],
            [1, WordGender::M, OffenseLevel::LOW, [7], 'Peureux'],
        ];
    }

    #[Test]
    #[DataProvider('randomGenderedQualifierDataProvider')]
    public function shouldGetGenderedQualifier(
        int $previousId,
        WordGender $targetGender,
        OffenseLevel $offenseLevel,
        array $exclusions,
        string $expectedLabel
    ): void {
        $result = ($this->underTest)(new GetWordCommand(
            role: GrammaticalRoleType::QUALIFIER,
            gender: $targetGender,
            // when requesting a new Subject, offenseLevel is <=
            offenseLevel: $offenseLevel,
            previousId: $previousId,
            exclusions: $exclusions
        ));

        self::assertEquals($expectedLabel, $result->label);
    }

    public static function randomQualifierDataProvider(): array
    {
        return [
            [WordGender::F, ['Peureuse', 'Indiscrète']],
            [WordGender::M, ['Peureux', 'Interrogateur']],
        ];
    }

    #[Test]
    #[DataProvider('randomQualifierDataProvider')]
    public function shouldRandomlyPickGenderedOrAutoQualifier(WordGender $targetGender, array $expectedLabels): void
    {
        $labels = [];
        for ($retries = 0; $retries < 30; ++$retries) {
            $result = ($this->underTest)(new GetWordCommand(
                role: GrammaticalRoleType::QUALIFIER,
                gender: $targetGender,
                // when requesting a new Subject, offenseLevel is <=
                offenseLevel: OffenseLevel::MAX,
                previousId: 1
            ));

            if (!isset($labels[$result->label])) {
                $labels[$result->label] = 0;
            }
            ++$labels[$result->label];
        }
        self::assertEqualsCanonicalizing($expectedLabels, array_keys($labels));

        // FIXME : on a small amount of retries, random distribution should not be tested this way
        // here, we only check that with 30 retries, each label appears 5 to 25 times which is basically the
        // same than not testing it
        foreach ($labels as $count) {
            self::assertTrue(abs($count - $retries / 2) <= 10);
        }
    }
}
