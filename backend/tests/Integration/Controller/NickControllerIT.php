<?php

namespace App\Tests\Integration\Controller;

use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Tests\Support\AltchaWebTestCase;
use App\Tests\Support\ApiUrl;
use App\Tests\Support\TestRequestParameters;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Wilhelm Zwertvaegher
 */
class NickControllerIT extends AltchaWebTestCase
{
    /**
     * @return list<array{string, list<int>, list<string>}>
     */
    public static function provideValidUrlQueryFoNewNick(): array
    {
        // we do not include the AUTO gender, because it cannot be a result of a nick generation, only a parameter
        $allPossibleGenders = ['F', 'M', 'NEUTRAL'];
        $allPossibleOffenseLevels = array_map(fn (OffenseLevel $level) => $level->value, OffenseLevel::cases());

        return [
            ['', $allPossibleOffenseLevels, $allPossibleGenders],
            ['offenseLevel=max', [OffenseLevel::MAX->value], $allPossibleGenders],
            ['offenseLevel=low', [OffenseLevel::LOW->value], $allPossibleGenders],
            ['gender=f', $allPossibleOffenseLevels, [WordGender::F->value]],
            ['gender=m', $allPossibleOffenseLevels, [WordGender::M->value]],
            ['gender=neutral', $allPossibleOffenseLevels, [WordGender::NEUTRAL->value]],
            ['gender=neutral&offenseLevel=20', [OffenseLevel::MAX->value], [WordGender::NEUTRAL->value]],
        ];
    }

    /**
     * @param list<int>    $expectedOffenseLevels
     * @param list<string> $expectedGenders
     */
    #[Test]
    #[DataProvider('provideValidUrlQueryFoNewNick')]
    public function shouldGenerateNick(string $query, array $expectedOffenseLevels, array $expectedGenders): void
    {
        $this->requestWithValidAltcha(
            new TestRequestParameters('GET', ApiUrl::build(ApiUrl::NICK_ENDPOINT, $query))
        );

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
        $this->requestWithValidAltcha(
            new TestRequestParameters(
                'GET',
                ApiUrl::build(ApiUrl::NICK_ENDPOINT, 'previousId=1&replaceRole=subject')
            )
        );

        self::assertResponseIsSuccessful();
        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);
        self::assertArrayHasKey('id', $data);
        self::assertNotEquals(1, $data['id']);
        self::assertEquals(WordGender::M->value, $data['gender']);
        self::assertEquals(OffenseLevel::MEDIUM->value, $data['offenseLevel']);
    }

    #[Test]
    public function whenAltchaIsInvalidThenShouldReturn401(): void
    {
        $this->requestWithInvalidAltcha(new TestRequestParameters('GET', ApiUrl::build(ApiUrl::NICK_ENDPOINT)));
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
