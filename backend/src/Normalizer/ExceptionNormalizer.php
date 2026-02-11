<?php

namespace App\Normalizer;

use App\Exception\ErrorCode;
use App\Translation\TranslatorInterface;
use Symfony\Component\Clock\ClockAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author Wilhelm Zwertvaegher
 */
abstract class ExceptionNormalizer implements NormalizerInterface
{
    use ClockAwareTrait;

    public function __construct(protected readonly TranslatorInterface $translator)
    {
    }

    /**
     * @return array<string, array<string, string[]|int[]>>
     */
    abstract protected function normalizeErrors(\Throwable $throwable): array;

    abstract protected function getStatus(\Throwable $throwable): int;

    protected function getErrorCode(\Throwable $throwable): ErrorCode
    {
        return ErrorCode::INTERNAL;
    }

    protected function getMessage(\Throwable $throwable): string
    {
        return $this->translator->translate($throwable->getMessage());
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return array<string, mixed>
     */
    final public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {
        return [
            'timestamp' => $this->now()->format(\DateTimeInterface::RFC3339_EXTENDED),
            'status' => $this->getStatus($data),
            'error' => $this->getErrorCode($data),
            'message' => $this->translator->translate($this->getMessage($data)),
            'errors' => $this->normalizeErrors($data),
        ];
    }
}
