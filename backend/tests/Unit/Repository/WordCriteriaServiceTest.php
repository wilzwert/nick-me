<?php

namespace App\Tests\Unit\Repository;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Specification\Criterion\GenderConstraintType;
use App\Specification\Criterion\GenderCriterion;
use App\Specification\Criterion\OffenseConstraintType;
use App\Specification\Criterion\OffenseLevelCriterion;
use App\Specification\DefaultCriterionConverter;
use App\Specification\WordCriteria;
use App\Specification\WordCriteriaService;
use App\Tests\Fakes\FakeQueryBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WordCriteriaServiceTest extends TestCase
{
    private WordCriteriaService $underTest;

    protected function setUp(): void
    {
        $this->underTest = new WordCriteriaService(new DefaultCriterionConverter());
    }

    #[Test]
    public function shouldApplyDefaultWordCriteria(): void
    {
        $queryBuilder = new FakeQueryBuilder();
        $queryBuilder->setExpectedCount(4);

        $this->underTest->applyWordCriteria($queryBuilder, new WordCriteria());

        // by default, a WordCriteria only has a set lang, which is Lang::FR by default
        self::assertCount(1, $queryBuilder->getWhere());
        self::assertContains('word.lang = :lang', $queryBuilder->getWhere());
        self::assertEquals(Lang::FR, $queryBuilder->getParameters()['lang']);
    }

    #[Test]
    public function shouldApplyDefaultWordCriteriaWithLang(): void
    {
        $queryBuilder = new FakeQueryBuilder();
        $queryBuilder->setExpectedCount(4);

        $this->underTest->applyWordCriteria($queryBuilder, new WordCriteria(Lang::EN));

        // by default, a WordCriteria only has a set lang, which is Lang::FR by default
        self::assertCount(1, $queryBuilder->getWhere());
        self::assertContains('word.lang = :lang', $queryBuilder->getWhere());
        self::assertEquals(Lang::EN, $queryBuilder->getParameters()['lang']);
    }

    public static function wordCriteriaProvider(): array
    {
        return [
            [
                new WordCriteria(
                    Lang::FR,
                    [
                        new GenderCriterion(
                            WordGender::F,
                            GenderConstraintType::EXACT
                        ),
                    ]
                ),
                ['word.lang = :lang', 'word.gender IN (:values0)'],
                ['lang', 'values0'],
                [Lang::FR, [WordGender::F, WordGender::AUTO]],
            ],
            [
                new WordCriteria(
                    Lang::EN,
                    [
                        new GenderCriterion(
                            WordGender::M,
                            GenderConstraintType::RELAXED
                        ),
                        new OffenseLevelCriterion(
                            OffenseLevel::MEDIUM,
                            OffenseConstraintType::LTE
                        ),
                    ]
                ),
                ['word.lang = :lang', 'word.gender IN (:values0)', 'word.offenseLevel IN (:values1)'],
                ['lang', 'values0', 'values1'],
                [Lang::EN, [WordGender::M, WordGender::AUTO, WordGender::NEUTRAL], [OffenseLevel::LOW, OffenseLevel::MEDIUM]],
            ],
        ];
    }

    #[Test]
    #[DataProvider('wordCriteriaProvider')]
    public function shouldApplyWordCriteria(
        WordCriteria $wordCriteria,
        array $expectedWHere,
        array $expectedParameterNames,
        array $expectedValues,
    ): void {
        $queryBuilder = new FakeQueryBuilder();
        $queryBuilder->setExpectedCount(4);

        $this->underTest->applyWordCriteria($queryBuilder, $wordCriteria);
        self::assertEquals($expectedWHere, $queryBuilder->getWhere());
        self::assertEquals($expectedParameterNames, array_keys($queryBuilder->getParameters()));
        self::assertEquals($expectedValues, array_values($queryBuilder->getParameters()));
    }
}
