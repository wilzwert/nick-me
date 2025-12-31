<?php

namespace App\Tests\Integration\UseCase;

use App\Dto\Command\GenerateNickCommand;
use App\Dto\Request\RandomNickRequest;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
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

    #[Test]function shouldGenerateNickWithComputedTargetGender(): void
    {
        self::bootKernel();
        $useCase = static::getContainer()->get(GenerateNickInterface::class);
        $result = ($useCase)(new GenerateNickCommand(Lang::FR, WordGender::AUTO));

        $words = $result->getWords();
        self::assertEquals($words[0]->type, GrammaticalRoleType::SUBJECT);
        self::assertEquals($words[1]->type, GrammaticalRoleType::QUALIFIER);

        // randomly computed
        self::assertNotEquals(WordGender::AUTO, $result->getTargetGender());
        self::assertNotEquals(WordGender::NEUTRAL, $result->getTargetGender());

    }
}
