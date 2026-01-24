<?php

namespace App\Tests\Integration\Controller;

use App\Tests\Support\AltchaWebTestCase;
use App\Tests\Support\ApiUrl;
use App\Tests\Support\TestRequestParameters;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

use function PHPUnit\Framework\assertNotEmpty;

/**
 * @author Wilhelm Zwertvaegher
 */
class SuggestionControllerIT extends AltchaWebTestCase
{
    #[Test]
    public function shouldCreateSuggestion(): void
    {
        $this->requestWithValidAltcha(
            new TestRequestParameters(
                method: 'POST',
                uri: ApiUrl::build(ApiUrl::SUGGESTION_ENDPOINT),
                server: [
                    'CONTENT_TYPE' => 'application/json',
                ],
                content: json_encode([
                    'senderEmail' => 'test@example.com',
                    'label' => 'MyNewWord',
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
                uri: ApiUrl::build(ApiUrl::SUGGESTION_ENDPOINT),
                server: [
                    'CONTENT_TYPE' => 'application/json',
                ],
                content: json_encode([
                ])
            )
        );
        self::assertResponseStatusCodeSame(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response = $this->client->getResponse();
        $jsonContent = json_decode($response->getContent());
        assertNotEmpty($jsonContent);
    }

    #[Test]
    public function whenWordExistsThenShouldReturn409(): void
    {
        $this->requestWithValidAltcha(
            new TestRequestParameters(
                method: 'POST',
                uri: ApiUrl::build(ApiUrl::SUGGESTION_ENDPOINT),
                server: [
                    'CONTENT_TYPE' => 'application/json',
                ],
                content: json_encode([
                    'label' => 'Nucleaire',
                ])
            )
        );
        self::assertResponseStatusCodeSame(Response::HTTP_CONFLICT);
        $response = $this->client->getResponse();
        $jsonContent = json_decode($response->getContent());
        assertNotEmpty($jsonContent);
    }

    #[Test]
    public function whenAltchaIsInvalidThenShouldReturn401(): void
    {
        $this->requestWithInvalidAltcha(new TestRequestParameters('POST', ApiUrl::build(ApiUrl::SUGGESTION_ENDPOINT)));
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
