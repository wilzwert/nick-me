<?php

namespace App\Tests\Unit\Service\Nick;

use App\Dto\Result\GeneratedNickWord;
use App\Dto\Result\GeneratedNickWords;
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
use App\Service\Nick\Strategy\NickComposerRules;
use App\Service\Nick\WordFormatterInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class NickComposerTest extends TestCase
{
    private NickComposer $nickComposer;

    private NickComposerRules&MockObject $frNickComposerRules;

    protected function setUp(): void
    {
        // NickComposer responsibility is to :
        //  - set words in the right order
        //  - pass those words to WordFormatterInterface and NickComposerRules
        //  - build GeneratedNickWords result with the passed target WordGender and the Subject's OffenseLevel
        // words alteration is (and must be) done only by strategies (i.e. NickComposerRules)
        // or WordFormatterInterface (which default implementation relies on WordRules)
        // We use mocks because we want to check the NickComposer calls the rules/formatter, as it is not just an
        // implementation detail but a mandatory behavior
        $this->frNickComposerRules = $this->createMock(NickComposerRules::class);
        $this->frNickComposerRules
            ->expects($this->once())
            ->method('getLang')
            ->willReturn(Lang::FR);

        $wordFormatter = $this->createMock(WordFormatterInterface::class);
        // word formatter should be called twice in each test because nicks have 2 words
        $wordFormatter
            ->expects($this->exactly(2))->method('format')
            ->willReturnCallback(fn (GrammaticalRole $grammaticalRole, WordGender $gender) => new GeneratedNickWord(
                $grammaticalRole->getWord()->getId(),
                $grammaticalRole->getWord()->getLabel(),
                GrammaticalRoleType::fromClass($grammaticalRole::class)
            ));

        $this->nickComposer = new NickComposer([$this->frNickComposerRules], $wordFormatter);
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

    /**
     * @return list<array{Subject, Qualifier, Lang, int, string}>
     */
    public static function getTestData(): array
    {
        // gender having no effect on the specific logic implemented by the NickComposer and tested here,
        // values passed to instantiateWord could be randomly chosen
        return [
            [
                new Subject(self::instantiateWord('Sacripan', WordGender::M, Lang::FR, OffenseLevel::LOW)),
                new Qualifier(self::instantiateWord('Joli', WordGender::AUTO, Lang::FR, OffenseLevel::MEDIUM), QualifierPosition::BEFORE),
                Lang::FR,
                1,
                'Joli Sacripan',
            ],
            [
                new Subject(self::instantiateWord('Sacripan', WordGender::M, Lang::FR, OffenseLevel::MEDIUM)),
                new Qualifier(self::instantiateWord('Elégant', WordGender::AUTO, Lang::FR, OffenseLevel::HIGH), QualifierPosition::AFTER),
                Lang::FR,
                1,
                'Sacripan Elégant',
            ],
            [
                new Subject(self::instantiateWord('Girl', WordGender::F, Lang::EN, OffenseLevel::LOW)),
                new Qualifier(self::instantiateWord('Nice', WordGender::AUTO, Lang::EN, OffenseLevel::MAX), QualifierPosition::BEFORE),
                Lang::EN,
                0,
                'Nice Girl',
            ],
            [
                new Subject(self::instantiateWord('Girl', WordGender::F, Lang::EN, OffenseLevel::MEDIUM)),
                new Qualifier(self::instantiateWord('with a hat', WordGender::AUTO, Lang::EN, OffenseLevel::MAX), QualifierPosition::AFTER),
                Lang::EN,
                0,
                'Girl with a hat',
            ],
        ];
    }

    #[Test]
    #[DataProvider('getTestData')]
    public function shouldComposeNick(Subject $subject, Qualifier $qualifier, Lang $lang, int $expectedComposerRulesCalls, string $expectedFinalLabel): void
    {
        if ($expectedComposerRulesCalls > 0) {
            $this->frNickComposerRules
                ->expects(self::exactly($expectedComposerRulesCalls))
                ->method('apply')
                ->willReturnCallback(fn (GeneratedNickWords $generatedNickWords, WordGender $targetGender) => $generatedNickWords);
        }
        $generatedNickWords = $this->nickComposer->compose($subject, $qualifier, $lang, WordGender::NEUTRAL);
        // check the final targetGender is the one passed to the composer
        self::assertEquals(WordGender::NEUTRAL, $generatedNickWords->getTargetGender());
        // check the final offense level comes from the subject
        self::assertEquals($subject->getWord()->getOffenseLevel(), $generatedNickWords->getTargetOffenseLevel());
        self::assertEquals($expectedFinalLabel, $generatedNickWords->getFinalLabel());
    }
}
