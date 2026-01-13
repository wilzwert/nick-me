<?php

namespace App\Security\Authenticator;

use App\Security\ApiUser;
use App\Security\Service\AltchaServiceInterface;
use PHPStan\DependencyInjection\AutowiredParameter;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

/**
 * @author Wilhelm Zwertvaegher
 */

class AltchaAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        private readonly AltchaServiceInterface $altchaService,
        #[Autowire('%altcha.header_payload_key%')]
        private readonly string $headerPayloadKey
    )
    {
    }

    public function authenticate(Request $request): Passport
    {
        $payload = $request->headers->get($this->headerPayloadKey);

        if (!$payload || !$this->altchaService->verifySolution($payload)) {
            throw new AuthenticationException('Captcha invalid');
        }

        return new SelfValidatingPassport(
            new UserBadge('frontend', fn (string $userIdentifier) => new ApiUser($userIdentifier, ['ROLE_FRONTEND']))
        );
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has($this->headerPayloadKey);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'message' => $exception->getMessage(),
        ];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }
}
