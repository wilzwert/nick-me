<?php

namespace App\Tests\Unit\Security\Authenticator;

use App\Security\ApiUser;
use App\Security\Authenticator\AltchaAuthenticator;
use App\Security\Authenticator\ClientAuthenticator;
use App\Tests\Support\AltchaTestData;
use App\Tests\Support\AppTestData;
use App\Tests\Support\Stub\FakeAltchaService;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * @author Wilhelm Zwertvaegher
 */
class ClientAuthenticatorTest extends TestCase
{
    private ClientAuthenticator $authenticator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authenticator = new ClientAuthenticator(AppTestData::CLIENT_API_KEY_HEADER, AppTestData::CLIENT_API_KEY);
    }

    #[Test]
    public function shouldSupportRequestWhenPayloadHeaderExists(): void
    {
        $request = new Request();
        $request->headers->set(AppTestData::CLIENT_API_KEY_HEADER, AppTestData::CLIENT_INVALID_API_KEY);
        self::assertTrue($this->authenticator->supports($request));
    }

    #[Test]
    public function shouldNotSupportRequestWhenPayloadHeaderMissing(): void
    {
        $request = new Request();
        self::assertFalse($this->authenticator->supports($request));
    }

    #[Test]
    public function shouldAuthenticateAndReturnPassportWhenPayloadIsValid(): void
    {
        $request = new Request();
        $request->headers->set(AppTestData::CLIENT_API_KEY_HEADER, AppTestData::CLIENT_API_KEY);

        $passport = $this->authenticator->authenticate($request);

        self::assertInstanceOf(SelfValidatingPassport::class, $passport);
        $userBadge = $passport->getBadge(UserBadge::class);
        self::assertInstanceOf(UserBadge::class, $userBadge);
        $user = ($userBadge->getUserLoader())('client');
        self::assertInstanceOf(ApiUser::class, $user);
        self::assertEquals(['ROLE_CLIENT'], $user->getRoles());
    }

    #[Test]
    public function whenInvalidThenShouldThrowException(): void
    {
        $request = new Request();
        $request->headers->set(AppTestData::CLIENT_API_KEY_HEADER, AppTestData::CLIENT_INVALID_API_KEY);
        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Invalid api key');

        $this->authenticator->authenticate($request);
    }

    #[Test]
    public function onAuthenticationFailureShouldReturnJsonResponseWithUnauthorized(): void
    {
        $request = new Request();
        $exception = new AuthenticationException('Invalid api key');

        $response = $this->authenticator->onAuthenticationFailure($request, $exception);

        self::assertInstanceOf(JsonResponse::class, $response);
        self::assertEquals(Response::HTTP_UNAUTHORIZED, $response->getStatusCode());
        self::assertEquals(['message' => 'Invalid api key'], json_decode($response->getContent(), true));
    }

    #[Test]
    public function onAuthenticationSuccessShouldReturnNull(): void
    {
        $request = new Request();
        $token = $this->createStub(TokenInterface::class);

        $response = $this->authenticator->onAuthenticationSuccess($request, $token, 'main');

        self::assertNull($response);
    }
}
