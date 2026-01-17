<?php

namespace App\Tests\Support;

use App\Security\Service\AltchaServiceInterface;
use App\Tests\Fakes\FakeAltchaService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;

/**
 * @author Wilhelm Zwertvaegher
 */
abstract class AltchaWebTestCase extends WebTestCase
{
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->altchaHeaderPayloadKey = 'HTTP_'.str_replace('-', '_', strtoupper(static::getContainer()->getParameter(
            'altcha.header_payload_key'
        )));
        self::getContainer()->set(AltchaServiceInterface::class, new FakeAltchaService());
    }

    protected function requestWithValidAltcha(TestRequestParameters $parameters): Crawler
    {
        $parameters->setServer($this->altchaHeaderPayloadKey, AltchaTestData::VALID_PAYLOAD);

        return $this->client->request(
            $parameters->getMethod(),
            $parameters->getUri(),
            $parameters->getParameters(),
            $parameters->getFiles(),
            $parameters->getServer(),
            $parameters->getContent(),
            $parameters->isChangeHistory()
        );
    }

    protected function requestWithInvalidAltcha(TestRequestParameters $parameters): Crawler
    {
        $parameters->setServer($this->altchaHeaderPayloadKey, AltchaTestData::INVALID_PAYLOAD);

        return $this->client->request(
            $parameters->getMethod(),
            $parameters->getUri(),
            $parameters->getParameters(),
            $parameters->getFiles(),
            $parameters->getServer(),
            $parameters->getContent(),
            $parameters->isChangeHistory()
        );
    }
}
