<?php

namespace App\Tests\Integration\Controller;

use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Wilhelm Zwertvaegher
 */
class NickControllerIT extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }

    public static function provideValidUrlQueryFoNewNick(): array
    {
        $allPossibleGenders = ['F', 'M', 'NEUTRAL'];
        $allPossibleOffenseLevels = array_map(fn (OffenseLevel $level) => $level->value, OffenseLevel::cases());
        return [
            ['', $allPossibleOffenseLevels, $allPossibleGenders ],
            ['offenseLevel=max', [OffenseLevel::MAX->value], $allPossibleGenders],
            ['offenseLevel=low', [OffenseLevel::LOW->value], $allPossibleGenders],
            ['gender=f', $allPossibleOffenseLevels, [WordGender::F->value]],
            ['gender=m', $allPossibleOffenseLevels, [WordGender::M->value]],
            ['gender=neutral', $allPossibleOffenseLevels, [WordGender::NEUTRAL->value]],
            ['gender=neutral&offenseLevel=20', [OffenseLevel::MAX->value], [WordGender::NEUTRAL->value]],
        ];
    }

    #[Test]
    #[DataProvider('provideValidUrlQueryFoNewNick')]
    public function shouldGenerateNick(string $query, array $expectedOffenseLevels, array $expectedGenders): void
    {
        $this->client->request('GET', sprintf('/api/nick?%s', $query));

        self::assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $data);
        self::assertCount(2, $data['words']);
        self::assertTrue(in_array($data['offenseLevel'], $expectedOffenseLevels));
        self::assertTrue(in_array($data['gender'], $expectedGenders));
    }

    #[Test]
    public function shouldUpdateNick(): void
    {
        $this->client->request('GET', '/api/nick?previousId=1&replaceRole=subject');

        self::assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $data);
        self::assertNotEquals(1, $data['id']);
        self::assertEquals(WordGender::M->value, $data['gender']);
        self::assertEquals(OffenseLevel::MEDIUM->value, $data['offenseLevel']);
    }
}
