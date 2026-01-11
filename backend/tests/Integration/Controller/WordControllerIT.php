<?php

namespace App\Tests\Integration\Controller;

use App\Enum\OffenseLevel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Wilhelm Zwertvaegher
 */
class WordControllerIT extends WebTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();
    }
    public static function provideValidUrlQueryFoNewWord(): array
    {
        return [
            ['previousId=3&role=subject&gender=NEUTRAL&offenseLevel='.OffenseLevel::MEDIUM->value],
            ['previousId=8&role=qualifier&gender=NEUTRAL&offenseLevel='.OffenseLevel::MAX->value],
        ];
    }

    #[Test]
    #[DataProvider('provideValidUrlQueryFoNewWord')]
    public function shouldGetWord(string $query): void
    {
        $this->client->request('GET', sprintf('/api/word?%s', $query));

        self::assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $data);
        self::assertArrayHasKey('label', $data);
        self::assertArrayHasKey('role', $data);

    }
}
