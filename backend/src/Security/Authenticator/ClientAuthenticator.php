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
class ClientAuthenticator extends AbstractAuthenticator
{
    public function __construct(
        #[Autowire('%client.api_key_header%')]
        private readonly string $clientApiKeyHeader,
        #[Autowire('%client.api_key%')]
        private readonly string $clientApiKey,
    ) {
    }

    public function authenticate(Request $request): Passport
    {
        $clientApiKey = $request->headers->get($this->clientApiKeyHeader);

        if (!$clientApiKey || $clientApiKey !== $this->clientApiKey) {
            throw new AuthenticationException('Invalid api key'.$clientApiKey. ' / '.$this->clientApiKey);
        }

        return new SelfValidatingPassport(
            new UserBadge('client', fn (string $userIdentifier) => new ApiUser($userIdentifier, ['ROLE_CLIENT']))
        );
    }

    public function supports(Request $request): ?bool
    {
        return $request->headers->has($this->clientApiKeyHeader);
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
