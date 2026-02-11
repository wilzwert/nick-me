<?php

namespace App\Tests\Integration\Controller;

use App\Enum\OffenseLevel;
use App\Tests\Support\AltchaWebTestCase;
use App\Tests\Support\ApiUrl;
use App\Tests\Support\TestRequestParameters;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Wilhelm Zwertvaegher
 */
class WordControllerIT extends AltchaWebTestCase
{
    /**
     * @return list<list<string>>
     */
    public static function provideValidUrlQueryForNewWord(): array
    {
        return [
            ['previousId=3&role=subject&gender=NEUTRAL&offenseLevel='.OffenseLevel::MEDIUM->value],
            ['previousId=8&role=qualifier&gender=NEUTRAL&offenseLevel='.OffenseLevel::MAX->value],
        ];
    }

    #[Test]
    #[DataProvider('provideValidUrlQueryForNewWord')]
    public function shouldGetWord(string $query): void
    {
        $this->requestWithValidAltcha(new TestRequestParameters('GET', ApiUrl::build(ApiUrl::WORD_ENDPOINT, $query)));

        self::assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $data);
        self::assertArrayHasKey('label', $data);
        self::assertArrayHasKey('role', $data);
    }

    #[Test]
    public function whenAltchaIsInvalidThenShouldReturn401(): void
    {
        $this->requestWithInvalidAltcha(new TestRequestParameters('GET', ApiUrl::build(ApiUrl::WORD_ENDPOINT)));
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
