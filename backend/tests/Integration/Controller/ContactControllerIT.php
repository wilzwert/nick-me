<?php

namespace App\Tests\Integration\Controller;

use App\Entity\Notification;
use App\Enum\OffenseLevel;
use App\Enum\WordGender;
use App\Repository\NotificationRepository;
use App\Tests\Support\AltchaWebTestCase;
use App\Tests\Support\ApiUrl;
use App\Tests\Support\TestRequestParameters;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\Response;

/**
 * @author Wilhelm Zwertvaegher
 */
class ContactControllerIT extends AltchaWebTestCase
{
    #[Test]
    public function shouldCreateMessage(): void
    {

        $messageRepository = self::getContainer()->get(NotificationRepository::class);
        $this->requestWithValidAltcha(
            new TestRequestParameters(
                method: 'POST',
                uri: ApiUrl::build(ApiUrl::CONTACT_ENDPOINT),
                server: [
                    'CONTENT_TYPE' => 'application/json',
                ],
                content: json_encode([
                    'senderEmail' => 'test@example.com',
                    'content' => 'This is a test message',
                ])
            )
        );

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);

        // check the Message has been created
        /** @var Notification[] $messages */
        $messages = $messageRepository->findAll();
        self::assertCount(1, $messages);
        self::assertNotNull($messages[0]->getId());
        self::assertSame('This is a test message', $messages[0]->getContent());
        self::assertSame('test@example.com', $messages[0]->getSenderEmail());
    }

    #[Test]
    public function whenAltchaIsInvalidThenShouldReturn401(): void
    {
        $this->requestWithInvalidAltcha(new TestRequestParameters('GET', ApiUrl::build(ApiUrl::NICK_ENDPOINT)));
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
