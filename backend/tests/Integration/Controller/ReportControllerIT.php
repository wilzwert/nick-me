<?php

namespace App\Tests\Integration\Controller;

use App\Tests\Support\AltchaWebTestCase;
use App\Tests\Support\ApiUrl;
use App\Tests\Support\AppTestData;
use App\Tests\Support\TestRequestParameters;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\assertNotEmpty;

/**
 * @author Wilhelm Zwertvaegher
 */
class ReportControllerIT extends AltchaWebTestCase
{
    #[Test]
    public function shouldCreateMessage(): void
    {
        $this->requestWithValidAltcha(
            new TestRequestParameters(
                method: 'POST',
                uri: ApiUrl::build(ApiUrl::REPORT_ENDPOINT),
                server: [
                    'CONTENT_TYPE' => 'application/json',
                ],
                content: json_encode([
                    'senderEmail' => 'test@example.com',
                    'reason' => 'Report reason',
                    'nickId' => AppTestData::EXISTING_NICK_ID,
                ])
            )
        );
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }

    #[Test]
    public function whenRequestInvalidThenShouldReturn422(): void
    {
        $this->requestWithValidAltcha(
            new TestRequestParameters(
                method: 'POST',
                uri: ApiUrl::build(ApiUrl::REPORT_ENDPOINT),
                server: [
                    'CONTENT_TYPE' => 'application/json',
                ],
                content: json_encode([
                    'senderEmail' => 'test',
                    'reason' => 'Report reason',
                ])
            )
        );
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response = $this->client->getResponse();
        $jsonContent = json_decode($response->getContent());
        assertNotEmpty($jsonContent);
    }

    #[Test]
    public function whenAltchaIsInvalidThenShouldReturn401(): void
    {
        $this->requestWithInvalidAltcha(new TestRequestParameters('POST', ApiUrl::build(ApiUrl::REPORT_ENDPOINT)));
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
