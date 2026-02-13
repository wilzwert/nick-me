<?php

namespace App\Security\Authenticator;

use App\Security\ApiUser;
use App\Security\Service\AltchaServiceInterface;
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
class InternalAppAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        #[Autowire('%internal_app.key_header%')]
        private readonly string $internalAppKeyHeader,
        #[Autowire('%internal_app.key%')]
        private readonly string $internalAppKey,
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        $appKey = $request->headers->get($this->internalAppKeyHeader);

        if (!$appKey || $appKey !== $this->internalAppKey) {
            throw new AuthenticationException('Invalid app key');
        }

        return new SelfValidatingPassport(
            new UserBadge('internal', fn (string $userIdentifier) => new ApiUser($userIdentifier, ['ROLE_INTERNAL'])),
        );
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has($this->internalAppKeyHeader);
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
