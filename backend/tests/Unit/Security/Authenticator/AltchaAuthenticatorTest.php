<?php

namespace App\Tests\Unit\Security\Authenticator;

use App\Security\ApiUser;
use App\Security\Authenticator\AltchaAuthenticator;
use App\Security\Service\AltchaServiceInterface;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * @author Wilhelm Zwertvaegher
 */
class AltchaAuthenticatorTest extends TestCase
{
    private const string ALTCHA_HEADER_KEY = 'altcha-header';

    private AltchaAuthenticator $authenticator;
    private MockObject&AltchaServiceInterface $altchaService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->altchaService = $this->createMock(AltchaServiceInterface::class);
        $this->authenticator = new AltchaAuthenticator($this->altchaService, self::ALTCHA_HEADER_KEY);
    }

    #[Test]
    public function shouldSupportRequestWhenPayloadHeaderExists(): void
    {
        $request = new Request();
        $request->headers->set(self::ALTCHA_HEADER_KEY, 'dummy');

        $this->altchaService
            ->expects($this->never())
            ->method('createChallenge');
        $this->altchaService
            ->expects($this->never())
            ->method('verifySolution');


        self::assertTrue($this->authenticator->supports($request));
    }

    #[Test]
    public function shouldNotSupportRequestWhenPayloadHeaderMissing(): void
    {
        $request = new Request();

        $this->altchaService
            ->expects($this->never())
            ->method('createChallenge');
        $this->altchaService
            ->expects($this->never())
            ->method('verifySolution');

        self::assertFalse($this->authenticator->supports($request));
    }

    #[Test]
    public function shouldAuthenticateAndReturnPassportWhenPayloadIsValid(): void
    {
        $request = new Request();
        $request->headers->set(self::ALTCHA_HEADER_KEY, 'validPayload');

        $this->altchaService
            ->expects($this->once())
            ->method('verifySolution')
            ->with('validPayload')
            ->willReturn(true);

        $passport = $this->authenticator->authenticate($request);

        self::assertInstanceOf(SelfValidatingPassport::class, $passport);
        $userBadge = $passport->getBadge(UserBadge::class);
        self::assertInstanceOf(UserBadge::class, $userBadge);
        $user = ($userBadge->getUserLoader())('frontend');
        self::assertInstanceOf(ApiUser::class, $user);
        self::assertEquals(['ROLE_FRONTEND'], $user->getRoles());
    }

    #[Test]
    public function whenInvalid_thenShouldThrowException(): void
    {
        $request = new Request();
        $request->headers->set(self::ALTCHA_HEADER_KEY, 'invalidPayload');

        $this->altchaService
            ->expects($this->once())
            ->method('verifySolution')
            ->with('invalidPayload')
            ->willReturn(false);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Captcha invalid');

        $this->authenticator->authenticate($request);
    }

    #[Test]
    public function onAuthenticationFailure_shouldReturnJsonResponseWithUnauthorized(): void
    {
        $request = new Request();
        $exception = new AuthenticationException('Captcha invalid');
        $this->altchaService
            ->expects($this->never())
            ->method('createChallenge');
        $this->altchaService
            ->expects($this->never())
            ->method('verifySolution');

        $response = $this->authenticator->onAuthenticationFailure($request, $exception);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(JsonResponse::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertEquals(['message' => 'Captcha invalid'], json_decode($response->getContent(), true));
    }

    #[Test]
    public function onAuthenticationSuccess_shouldReturnNull(): void
    {
        $request = new Request();
        $token = $this->createStub(TokenInterface::class);
        $this->altchaService
            ->expects($this->never())
            ->method('createChallenge');
        $this->altchaService
            ->expects($this->never())
            ->method('verifySolution');

        $response = $this->authenticator->onAuthenticationSuccess($request, $token, 'main');

        self::assertNull($response);
    }
}
