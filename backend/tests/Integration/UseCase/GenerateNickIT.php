<?php

namespace App\Tests\Integration\UseCase;

use App\Dto\Command\GenerateNickCommand;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\UseCase\GenerateNickInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class GenerateNickIT extends KernelTestCase
{
    #[Test]
    public function shouldGenerateNick(): void
    {
        self::bootKernel();
        $useCase = static::getContainer()->get(GenerateNickInterface::class);
        $result = ($useCase)(new GenerateNickCommand(Lang::FR));

        $words = $result->getWords();
        self::assertEquals($words[0]->type, GrammaticalRoleType::SUBJECT);
        self::assertEquals($words[1]->type, GrammaticalRoleType::QUALIFIER);
    }

    public static function targetGenders(): array
    {
        return [
            [WordGender::F],
            [WordGender::M],
            [WordGender::NEUTRAL],
        ];
    }

    #[Test]
    #[DataProvider('targetGenders')]
    public function shouldGenerateNickWithProvidedTargetGender(WordGender $targetGender): void
    {
        self::bootKernel();
        $useCase = static::getContainer()->get(GenerateNickInterface::class);
        $result = ($useCase)(new GenerateNickCommand(Lang::FR, $targetGender));

        $words = $result->getWords();
        self::assertEquals($words[0]->type, GrammaticalRoleType::SUBJECT);
        self::assertEquals($words[1]->type, GrammaticalRoleType::QUALIFIER);

        self::assertEquals($targetGender, $result->getTargetGender());
    }

    #[Test]
    public function shouldGenerateNickWithComputedTargetGender(): void
    {
        self::bootKernel();
        $useCase = static::getContainer()->get(GenerateNickInterface::class);
        $result = ($useCase)(new GenerateNickCommand(Lang::FR, WordGender::AUTO));

        $words = $result->getWords();
        self::assertEquals($words[0]->type, GrammaticalRoleType::SUBJECT);
        self::assertEquals($words[1]->type, GrammaticalRoleType::QUALIFIER);

        // randomly computed MUST NOT be AUTO or NEUTRAL
        self::assertNotEquals(WordGender::AUTO, $result->getTargetGender());
        self::assertNotEquals(WordGender::NEUTRAL, $result->getTargetGender());
    }

    #[Test]
    public function shouldUpdatePreviousNickSubject(): void
    {
        self::bootKernel();
        $useCase = static::getContainer()->get(GenerateNickInterface::class);
        $result = ($useCase)(new GenerateNickCommand(
            lang: Lang::FR,
            gender: WordGender::F,
            offenseLevel: OffenseLevel::MAX,
            previousNickId: 1,
            replaceRoleType: GrammaticalRoleType::SUBJECT,
        ));

        $words = $result->getWords();
        self::assertEquals($words[0]->type, GrammaticalRoleType::SUBJECT);
        self::assertNotEquals($words[0]->label, 'Camembert');
        self::assertEquals($words[1]->type, GrammaticalRoleType::QUALIFIER);
        self::assertEquals($words[1]->label, 'Fataliste');
        // previous nick's offense level and gender take precedence over the request
        self::assertEquals(OffenseLevel::MEDIUM, $result->getTargetOffenseLevel());
        self::assertEquals(WordGender::M, $result->getTargetGender());
    }

    #[Test]
    public function shouldUpdatePreviousNickQualifier(): void
    {
        self::bootKernel();
        $useCase = static::getContainer()->get(GenerateNickInterface::class);
        $result = ($useCase)(new GenerateNickCommand(
            lang: Lang::FR,
            gender: WordGender::F,
            offenseLevel: OffenseLevel::MAX,
            previousNickId: 1,
            replaceRoleType: GrammaticalRoleType::QUALIFIER,
        ));

        $words = $result->getWords();
        self::assertEquals($words[0]->type, GrammaticalRoleType::SUBJECT);
        self::assertEquals($words[0]->label, 'Camembert');
        self::assertEquals($words[1]->type, GrammaticalRoleType::QUALIFIER);
        self::assertNotEquals($words[1]->label, 'Fataliste');
        // previous nick's offense level and gender take precedence over the request
        self::assertEquals(OffenseLevel::MEDIUM, $result->getTargetOffenseLevel());
        self::assertEquals(WordGender::M, $result->getTargetGender());
    }

}
