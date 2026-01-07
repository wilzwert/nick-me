<?php

namespace App\Tests\Unit\Dto\Request;

use App\Dto\Request\EnumConverter;
use App\Dto\Request\RandomNickRequest;
use App\Dto\Request\RandomWordRequest;
use App\Dto\Request\RequestFactory;
use App\Enum\GrammaticalRoleType;
use App\Enum\Lang;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class RequestFactoryTest extends TestCase
{
    private RequestFactory $underTest;

    protected function setUp(): void
    {
        $this->underTest = new RequestFactory(new EnumConverter());
    }

    #[Test]
    public function whenUnknownClassThenShouldThrowInvalidArgumentException(): void
    {
        self::expectException(\InvalidArgumentException::class);
        $this->underTest->fromParameters(\stdClass::class, []);
    }

    #[Test]
    public function shouldBuildRandomNickRequest(): void
    {
        $parameters = [
            'offenseLevel' => 'max',
            'gender' => 'm',
            'exclusions' => '12,14',
        ];

        $result = $this->underTest->fromParameters(RandomNickRequest::class, $parameters);

        self::assertInstanceOf(RandomNickRequest::class, $result);
        self::assertSame(Lang::FR, $result->getLang());
        self::assertSame(OffenseLevel::MAX, $result->getOffenseLevel());
        self::assertSame(WordGender::M, $result->getGender());
        self::assertCount(2, $result->getExclusions());
    }

    #[Test]
    public function shouldBuildRandomWordRequest(): void
    {
        $parameters = [
            'previousId' => '8',
            'role' => 'subject',
            'offenseLevel' => 'max',
            'gender' => 'f',
        ];

        $result = $this->underTest->fromParameters(RandomWordRequest::class, $parameters);

        self::assertInstanceOf(RandomWordRequest::class, $result);
        self::assertSame(8, $result->getPreviousId());
        self::assertSame(GrammaticalRoleType::SUBJECT, $result->getGrammaticalRoleType());
        self::assertSame(WordGender::F, $result->getGender());
        self::assertSame(OffenseLevel::MAX, $result->getOffenseLevel());
        self::assertCount(0, $result->getExclusions());
    }
}
