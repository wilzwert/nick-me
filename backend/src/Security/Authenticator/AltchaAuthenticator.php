<?php

namespace App\Security\Authenticator;

use App\Security\ApiUser;
use App\Security\Service\AltchaServiceInterface;
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
    public function __construct(private readonly AltchaServiceInterface $altchaService)
    {
    }

    public function authenticate(Request $request): Passport
    {
        $payload = $request->headers->get('X-Altcha-Payload');

        if (!$payload || !$this->altchaService->verifySolution($payload)) {
            throw new AuthenticationException('Captcha invalid');
        }

        return new SelfValidatingPassport(
            new UserBadge('frontend', fn (string $userIdentifier) => new ApiUser($userIdentifier, ['ROLE_FRONTEND']))
        );
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has('X-Altcha-Payload');
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
