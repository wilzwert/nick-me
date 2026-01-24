<?php

namespace App\Normalizer;

use App\Exception\DomainException;
use App\Exception\ErrorCode;
use App\Exception\WordAlreadyExistsException;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\HttpFoundation\Response;

#[AutoconfigureTag('app.exception_normalizer')]
class DomainExceptionNormalizer extends ExceptionNormalizer
{
    /**
     * @var array<class-string, int>
     */
    private static array $status_codes = [
        WordAlreadyExistsException::class => Response::HTTP_CONFLICT,
    ];

    /**
     * @param array<string, mixed> $context
     */
    public function supportsNormalization(mixed $data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof DomainException;
    }

    /**
     * @return array<string, array<string, string[]|int[]>>
     */
    protected function normalizeErrors(\Throwable $throwable): array
    {
        if (!$throwable instanceof DomainException) {
            throw new \InvalidArgumentException();
        }

        return [];
    }

    /**
     * @param DomainException $throwable
     */
    #[\Override]
    protected function getErrorCode(\Throwable $throwable): ErrorCode
    {
        return match ($throwable::class) {
            WordAlreadyExistsException::class => ErrorCode::ENTITY_EXISTS,
            default => parent::getErrorCode($throwable),
        };
    }

    public function getSupportedTypes(?string $format): array
    {
        return [DomainException::class => true];
    }

    protected function getStatus(\Throwable $throwable): int
    {
        return self::$status_codes[$throwable::class] ?? Response::HTTP_BAD_REQUEST;
    }
}
