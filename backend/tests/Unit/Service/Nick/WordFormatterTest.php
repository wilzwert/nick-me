<?php

namespace App\Tests\Unit\Service\Nick;

use App\Dto\Result\GeneratedNickWord;
use App\Entity\GrammaticalRole;
use App\Entity\Qualifier;
use App\Entity\Subject;
use App\Entity\Word;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\QualifierPosition;
use App\Enum\WordGender;
use App\Enum\WordStatus;
use App\Service\Nick\NickComposer;
use App\Service\Nick\Strategy\WordRules;
use App\Service\Nick\WordFormatter;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class WordFormatterTest extends TestCase
{
    private WordFormatter $wordFormatter;

    private WordRules&MockObject $frWordRules;

    protected function setUp(): void
    {
        // WordFormatter responsibility is to :
        //  - pass a word to WordRules if available
        //  - build a GeneratedNickWord with a common format applied
        // words alteration is (and must be) done only by strategies (i.e. WordRules)
        // We use mocks because we want to check the WordFormatter calls the rules, as it is not just an
        // implementation detail but a mandatory behavior
        $this->frWordRules = $this->createMock(WordRules::class);
        $this->frWordRules
            ->expects($this->once())
            ->method('getLang')
            ->willReturn(Lang::FR);

        $this->wordFormatter = new WordFormatter([$this->frWordRules]);
    }

    private static function instantiateWord(string $label, WordGender $gender, Lang $lang = Lang::FR, OffenseLevel $offenseLevel = OffenseLevel::MEDIUM): Word
    {
        static $reflectionProperty = null;
        if (null === $reflectionProperty) {
            $reflectionClass = new \ReflectionClass(Word::class);
            $reflectionProperty = $reflectionClass->getProperty('id');
            $reflectionProperty->setAccessible(true);
        }

        $word = new Word(strtolower($label), $label, $gender, $lang, $offenseLevel, WordStatus::APPROVED, $d = new \DateTimeImmutable(), $d);
        // word id is not relevant to tests in this class, so we don't care about uniqueness
        $reflectionProperty->setValue($word, microtime(true));

        return $word;
    }

    public static function getTestData(): array
    {
        // gender having no effect on the specific logic implemented by the NickComposer and tested here,
        // values passed to instantiateWord could be randomly chosen
        return [
            [
                new Subject(static::instantiateWord('bOIT-SaNs-SOIF ', WordGender::M, Lang::FR, OffenseLevel::LOW)),
                1,
                'Boit-sans-soif',
                GrammaticalRoleType::SUBJECT,
            ],
            [
                new Qualifier(static::instantiateWord('ElégaNt', WordGender::AUTO, Lang::FR, OffenseLevel::HIGH), QualifierPosition::AFTER),
                1,
                'Elégant',
                GrammaticalRoleType::QUALIFIER,
            ],
            [
                new Subject(static::instantiateWord('VOdka ON the RoCks', WordGender::F, Lang::EN, OffenseLevel::LOW)),
                0,
                'Vodka on the rocks',
                GrammaticalRoleType::SUBJECT,
            ],
            [
                new Qualifier(static::instantiateWord('wiTh A Hat', WordGender::AUTO, Lang::EN, OffenseLevel::MAX), QualifierPosition::AFTER),
                0,
                'With a hat',
                GrammaticalRoleType::QUALIFIER,
            ],
        ];
    }

    #[Test]
    #[DataProvider('getTestData')]
    public function shouldFormWord(GrammaticalRole $grammaticalRole, int $expectedRulesCalls, string $expectedLabel, GrammaticalRoleType $expectedGrammaticalRoleType): void
    {
        if ($expectedRulesCalls > 0) {
            $this->frWordRules
                ->expects(self::exactly($expectedRulesCalls))
                ->method('resolve')
                ->willReturnCallback(fn (GrammaticalRole $grammaticalRole, WordGender $targetGender) => new GeneratedNickWord(
                    $grammaticalRole->getWord()->getId(),
                    $grammaticalRole->getWord()->getLabel(),
                    GrammaticalRoleType::fromClass($grammaticalRole::class)
                )
                );
        }
        $generatedNickWord = $this->wordFormatter->format($grammaticalRole, WordGender::NEUTRAL);
        self::assertEquals($grammaticalRole->getWord()->getId(), $generatedNickWord->id);
        self::assertEquals($expectedLabel, $generatedNickWord->label);
        self::assertEquals($expectedGrammaticalRoleType, $generatedNickWord->type);
    }
}
