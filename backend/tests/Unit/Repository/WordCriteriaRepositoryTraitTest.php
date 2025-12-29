<?php

namespace App\Tests\Unit\Repository;

use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Repository\WordCriteriaRepositoryTrait;
use App\Specification\GenderConstraintType;
use App\Specification\GenderCriterion;
use App\Specification\OffenseConstraintType;
use App\Specification\OffenseLevelCriterion;
use App\Specification\WordCriteria;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WordCriteriaRepositoryTraitTest extends TestCase
{

    use WordCriteriaRepositoryTrait;

    private function assertCommonBehaviour(MockObject&QueryBuilder $queryBuilder): void
    {
        // check the randomization is handled
        $queryBuilder->expects($this->once())
            ->method('setFirstResult')
            ->with(self::callback(fn ($offset) => is_int($offset)))
            ->willReturnSelf();

        $queryBuilder->expects($this->once())
            ->method('select')
            ->with('COUNT(word.id)')
            ->willReturnSelf();

        $query = $this->createMock(Query::class);
        $query->expects($this->once())
            ->method('getSingleScalarResult')
            ->willReturn(rand(1, 5));

        $queryBuilder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        // check the limit to one result is set
        $queryBuilder->expects($this->once())
            ->method('setMaxResults')
            ->with(1)
            ->willReturnSelf();

    }

    #[Test]
    public function shouldApplyDefaultWordCriteria(): void
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);

        // by default, a WordCriteria only has a set lang, which is Lang::FR by default

        $this->assertCommonBehaviour($queryBuilder);
        // checks that the lang is handled
        $queryBuilder->expects($this->once())
            ->method('andWhere')
            ->with('word.lang = :lang')
            ->willReturnSelf();

        $queryBuilder->expects($this->once())
            ->method('setParameter')
            ->with('lang', Lang::FR)
            ->willReturnSelf();


        $this->applyWordCriteria($queryBuilder, new WordCriteria());
    }

    #[Test]
    public function shouldApplyDefaultWordCriteriaWithLang(): void
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);

        // by default, a WordCriteria only has a set lang, which is Lang::FR by default

        $this->assertCommonBehaviour($queryBuilder);
        // checks that the lang is handled
        $queryBuilder->expects($this->once())
            ->method('andWhere')
            ->with('word.lang = :lang')
            ->willReturnSelf();

        $queryBuilder->expects($this->once())
            ->method('setParameter')
            ->with('lang', Lang::EN)
            ->willReturnSelf();


        $this->applyWordCriteria($queryBuilder, new WordCriteria(Lang::EN));
    }

    public static function wordCriteriaProvider(): array
    {
        return [
            [
                new WordCriteria(
                    Lang::FR,
                    null,
                    [
                        new GenderCriterion(
                            WordGender::F,
                            GenderConstraintType::EXACT
                        )
                    ]
                ),
                2,
                ["word.lang = :lang", "word.gender IN (:values0)"],
                ['lang', 'values0'],
                [Lang::FR, [WordGender::F, WordGender::AUTO]]
            ],
            [
                new WordCriteria(
                    Lang::EN,
                    null,
                    [
                        new GenderCriterion(
                            WordGender::M,
                            GenderConstraintType::RELAXED
                        ),
                        new OffenseLevelCriterion(
                            OffenseLevel::MEDIUM,
                            OffenseConstraintType::LTE
                        )
                    ]
                ),
                3,
                ['word.lang = :lang', 'word.gender IN (:values0)', 'word.offenseLevel IN (:values1)'],
                ['lang', 'values0', 'values1'],
                [Lang::EN, [WordGender::M, WordGender::AUTO, WordGender::NEUTRAL], [OffenseLevel::LOW, OffenseLevel::MEDIUM]]
            ]
        ];
    }

    #[Test]
    #[DataProvider('wordCriteriaProvider')]
    public function shouldApplyWordCriteria(
        WordCriteria $wordCriteria,
        int $expectedCalls,
        array $expectedWHere,
        array $expectedParameterNames,
        array $expectedValues
    ): void
    {
        $queryBuilder = $this->createMock(QueryBuilder::class);

        // by default, a WordCriteria only has a set lang, which is Lang::FR by default

        $this->assertCommonBehaviour($queryBuilder);

        $andWhereArgs = $parametersNamesArgs = $parametersValuesArgs = [];

        // checks that the lang is handled
        $queryBuilder->expects($this->exactly($expectedCalls))
            ->method('andWhere')
            ->with(
                self::callback(function ($arg) use (&$andWhereArgs) {
                    $andWhereArgs[] = $arg;
                    return true;
                }))
            ->willReturnSelf();

        $queryBuilder->expects($this->exactly($expectedCalls))
            ->method('setParameter')
            ->with(
                self::callback(function ($name) use (&$parametersNamesArgs) {
                    $parametersNamesArgs[] = $name;
                    return true;
                }),
                self::callback(function ($value) use (&$parametersValuesArgs) {
                    $parametersValuesArgs[] = $value;
                    return true;
                })
            )
            ->willReturnSelf();

        $this->applyWordCriteria($queryBuilder, $wordCriteria);

        self::assertEquals($expectedWHere, $andWhereArgs);;
        self::assertEquals($expectedParameterNames, $parametersNamesArgs);
        self::assertEquals($expectedValues, $parametersValuesArgs);
    }

}
