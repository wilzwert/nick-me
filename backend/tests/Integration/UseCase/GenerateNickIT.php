<?php

namespace App\Tests\Integration\UseCase;

use App\Dto\Request\RandomWordRequest;
use App\Enum\WordType;
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
        $result = ($useCase)(new RandomWordRequest());

        self::assertEquals($result->words[0]->type, WordType::SUBJECT);
        self::assertEquals($result->words[1]->type, WordType::QUALIFIER);
    }
}
