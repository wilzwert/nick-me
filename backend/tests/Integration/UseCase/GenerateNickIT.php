<?php

namespace App\Tests\Integration\UseCase;

use App\Dto\Request\RandomNickRequest;
use App\Enum\GrammaticalRoleType;
use App\UseCase\GenerateNickInterface;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class GenerateNickIT extends KernelTestCase
{
    #[Test]
    public function testGenerateNickIT(): void
    {
        self::bootKernel();
        $useCase = static::getContainer()->get(GenerateNickInterface::class);
        $result = ($useCase)(new RandomNickRequest());

        self::assertEquals($result->words[0]->type, GrammaticalRoleType::SUBJECT);
        self::assertEquals($result->words[1]->type, GrammaticalRoleType::QUALIFIER);
    }
}
