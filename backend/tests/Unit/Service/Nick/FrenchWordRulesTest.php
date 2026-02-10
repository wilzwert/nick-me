<?php

namespace App\Tests\Unit\Service\Nick;

use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use App\Service\Nick\Strategy\FrenchWordRules;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FrenchWordRulesTest extends TestCase
{
    private FrenchWordRules $rules;

    private \ReflectionProperty $reflectionProperty;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rules = new FrenchWordRules();
        $reflectionClass = new \ReflectionClass(Word::class);
        $this->reflectionProperty = $reflectionClass->getProperty('id');
        $this->reflectionProperty->setAccessible(true);
    }

    #[Test]
    public function shouldBeFr(): void
    {
        self::assertEquals(Lang::FR, $this->rules->getLang());
    }

    public static function getTestData(): array
    {
        return [
            [GrammaticalRoleType::SUBJECT, 'peureux', WordGender::M, WordGender::M, 'peureux'],
            [GrammaticalRoleType::SUBJECT, 'peureux', WordGender::AUTO, WordGender::F, 'peureuse'],
            [GrammaticalRoleType::SUBJECT, 'peureux', WordGender::M, WordGender::F, 'peureux'],
            [GrammaticalRoleType::SUBJECT, 'rêveur', WordGender::AUTO, WordGender::F, 'rêveuse'],
            [GrammaticalRoleType::SUBJECT, 'bastien', WordGender::AUTO, WordGender::F, 'bastienne'],
            [GrammaticalRoleType::SUBJECT, 'furtif', WordGender::AUTO, WordGender::F, 'furtive'],
            [GrammaticalRoleType::SUBJECT, 'fluet', WordGender::AUTO, WordGender::F, 'fluette'],
            [GrammaticalRoleType::SUBJECT, 'bon', WordGender::AUTO, WordGender::F, 'bonne'],
        ];
    }

    #[Test]
    #[DataProvider('getTestData')]
    public function shouldResolve(
        GrammaticalRoleType $grammaticalRoleType,
        string $label,
        WordGender $wordGender,
        WordGender $targetGender,
        string $expectedLabel,
    ): void {
        $time = new \DateTimeImmutable();
        $word = new Word('slug', $label, $wordGender, Lang::FR, OffenseLevel::MEDIUM, WordStatus::APPROVED, $time, $time);
        $id = rand(0, 1000);
        $this->reflectionProperty->setValue($word, $id);
        $grammaticalRole = match ($grammaticalRoleType) {
            GrammaticalRoleType::QUALIFIER => new Qualifier($word, QualifierPosition::BEFORE),
            GrammaticalRoleType::SUBJECT => new Subject($word),
        };

        $word = $this->rules->resolve($grammaticalRole, $targetGender);
        self::assertEquals($id, $word->id);
        self::assertEquals($expectedLabel, $word->label);
        self::assertEquals(GrammaticalRoleType::fromClass($grammaticalRole::class), $word->type);
    }
}
