<?php

namespace App\Tests\Unit\Service\Nick\Strategy;

use App\Dto\Result\GeneratedNickWord;
use App\Dto\Result\GeneratedNickWords;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Service\Nick\Strategy\FrenchNickComposerRules;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class FrenchNickComposerRulesTest extends TestCase
{
    private FrenchNickComposerRules $rules;

    private \ReflectionProperty $reflectionProperty;

    protected function setUp(): void
    {
        parent::setUp();
        $this->rules = new FrenchNickComposerRules();
        $reflectionClass = new \ReflectionClass(Word::class);
        $this->reflectionProperty = $reflectionClass->getProperty('id');
        $this->reflectionProperty->setAccessible(true);
    }

    #[Test]
    public function shouldBeFr(): void
    {
        self::assertEquals(Lang::FR, $this->rules->getLang());
    }

    /**
     * @return list<array{GrammaticalRoleType, GrammaticalRoleType, string, string ,WordGender, OffenseLevel, string}>
     */
    public static function getTestData(): array
    {
        return [
            [GrammaticalRoleType::SUBJECT, GrammaticalRoleType::QUALIFIER, 'personne', 'peureuse', WordGender::F, OffenseLevel::MEDIUM, 'personne peureuse'],
            [GrammaticalRoleType::QUALIFIER, GrammaticalRoleType::SUBJECT, 'espèce de', 'poule', WordGender::F, OffenseLevel::MEDIUM, 'espèce de poule'],
            [GrammaticalRoleType::QUALIFIER, GrammaticalRoleType::SUBJECT, 'espèce de', 'âne', WordGender::M, OffenseLevel::MEDIUM, "espèce d'âne"],
        ];
    }

    #[Test]
    #[DataProvider('getTestData')]
    public function shouldApply(
        GrammaticalRoleType $firstGrammaticalRoleType,
        GrammaticalRoleType $secondGrammaticalRoleType,
        string $firstLabel,
        string $secondLabel,
        WordGender $targetGender,
        OffenseLevel $targetOffenseLevel,
        string $expectedLabel,
    ): void {
        $id = rand(0, 1000);
        $words = new GeneratedNickWords(
            $targetGender,
            $targetOffenseLevel,
            [
                new GeneratedNickWord($id, $firstLabel, $firstGrammaticalRoleType),
                new GeneratedNickWord($id, $secondLabel, $secondGrammaticalRoleType),
            ]
        );
        $result = $this->rules->apply($words, $targetGender);
        self::assertEquals($targetGender, $result->getTargetGender());
        self::assertEquals($targetOffenseLevel, $result->getTargetOffenseLevel());
        self::assertEquals($expectedLabel, $result->getFinalLabel());
    }
}
