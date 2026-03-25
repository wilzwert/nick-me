<?php

namespace App\Tests\Unit\Specification;

use App\Enum\Enum;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Specification\Criteria;
use App\Specification\Criterion\GenderConstraintType;
use App\Specification\Criterion\GenderCriterion;
use App\Specification\Criterion\LangCriterion;
use App\Specification\Criterion\OffenseConstraintType;
use App\Specification\Criterion\OffenseLevelCriterion;
use App\Specification\DefaultCriterionConverter;
use App\Specification\WordCriteriaApplier;
use App\Tests\Support\Fake\FakeQueryBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class WordCriteriaBuilderTest extends TestCase
{
    private WordCriteriaApplier $underTest;

    protected function setUp(): void
    {
        $this->underTest = new WordCriteriaApplier(new DefaultCriterionConverter());
    }

    #[Test]
    public function shouldApplyDefaultWordCriteria(): void
    {
        $queryBuilder = new FakeQueryBuilder();
        $queryBuilder->setExpectedCount(4);

        $this->underTest->applyWordCriteria($queryBuilder, new Criteria());

        // by default, a Criteria has no criteria
        self::assertCount(0, $queryBuilder->getWhere());
    }

    #[Test]
    public function shouldApplyDefaultWordCriteriaWithLang(): void
    {
        $queryBuilder = new FakeQueryBuilder();
        $queryBuilder->setExpectedCount(4);

        $this->underTest->applyWordCriteria($queryBuilder, new Criteria([new LangCriterion(Lang::EN)]));

        // by default, a WordCriteria only has a set lang, which is Lang::FR by default
        self::assertCount(1, $queryBuilder->getWhere());
        var_dump($queryBuilder->getWhere());
        self::assertContains('word.lang = :value0', $queryBuilder->getWhere());
        self::assertEquals(Lang::EN, $queryBuilder->getParameters()['value0']);
    }

    /**
     * @return list<array{Criteria, list<string>, list<string>, list<Lang|list<Enum>>}>
     */
    public static function wordCriteriaProvider(): array
    {
        return [
            [
                new Criteria(
                    [
                        new LangCriterion(Lang::FR),
                        new GenderCriterion(
                            WordGender::F,
                            GenderConstraintType::EXACT
                        ),
                    ]
                ),
                ['word.lang = :value0', 'word.gender IN (:values1)'],
                ['value0', 'values1'],
                [Lang::FR, [WordGender::F, WordGender::AUTO]],
            ],
            [
                new Criteria(
                    [
                        new LangCriterion(Lang::EN),
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
                ['word.lang = :value0', 'word.gender IN (:values1)', 'word.offenseLevel IN (:values2)'],
                ['value0', 'values1', 'values2'],
                [Lang::EN, [WordGender::M, WordGender::AUTO, WordGender::NEUTRAL], [OffenseLevel::LOW, OffenseLevel::MEDIUM]],
            ],
        ];
    }

    /**
     * @param list<string>          $expectedWHere
     * @param list<string>          $expectedParameterNames
     * @param list<Lang|list<Enum>> $expectedValues
     */
    #[Test]
    #[DataProvider('wordCriteriaProvider')]
    public function shouldApplyWordCriteria(
        Criteria $wordCriteria,
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
